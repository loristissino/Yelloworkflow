<?php

namespace app\models\sm;

use Yii;

/**
 * This is the model class for accessing Mastodon services.
 */

class Mastodon
{
    private $mastodon_app;
    
    public function __construct($params)
    {
        /* $params should be an array with the following keys:
            'api_entry_point' => 'https://mastodon.example',
            'client_id' => '...theId...',
            'client_secret' => '...theSecret...'
        */

        $this->mastodon_app = $params;
    }
    
    public function startSession()
    {
        $info = $this->_send('/oauth/token', [
            'client_id' => $this->mastodon_app['client_id'],
            'client_secret' => $this->mastodon_app['client_secret'],
            'redirect_uri' => 'urn:ietf:wg:oauth:2.0:oob',
            'grant_type' => 'client_credentials',
            'scope' => 'read write',
        ], 'POST');
        
        if (isset($info['access_token'])) {
            Yii::$app->session['mastodon_access_token'] = $info['access_token'];
            return true;
        }
        unset(Yii::$app->session['mastodon_access_token']);
        return false;
    }
    
    public function endSession()
    {
        $info = $this->_send('/oauth/revoke', [
            'client_id' => $this->mastodon_app['client_id'],
            'client_secret' => $this->mastodon_app['client_secret'],
            'token' => Yii::$app->session['mastodon_access_token'],
        ], 'POST');
        
        if ($info=='') {
            unset(Yii::$app->session['mastodon_access_token']);
            return true;
        }
        return false;
    }
    
    public function isSessionActive()
    {
        return isset($this->verifyCredentials()['vapid_key']);
    }
    
    public function verifyCredentials()
    {
        return $this->_send('/api/v1/apps/verify_credentials');
    }
    
    public function post($status)
    {
        $info = $this->_send('/api/v1/statuses', ['status'=>'test'], 'POST');
    }
    
    private function _send($path, $contents = [], $method='GET')
    {
        $http_options = [
            'method'=>$method,
            'ignore_errors' => true,
        ];

        $headers = [
            'Content-type: application/x-www-form-urlencoded',
        ];
        
        if (isset(Yii::$app->session['mastodon_access_token'])) {
            $headers[] = 'Authorization: Bearer ' . Yii::$app->session['mastodon_access_token'];
        }

        if ($contents) {
            $data = http_build_query($contents);
            $headers[] = 'Content-Length: ' . strlen($data);
            $http_options['content']  = $data;
        }
        
        $http_options['header'] = implode("\r\n", $headers);
                
        try {
            $response = file_get_contents($this->mastodon_app['api_entry_point'] . $path, false, stream_context_create([
                'http'=> $http_options,
            ]));
        }
        catch (Exception $e) {
            die('no internet connection');
        }
        
        return json_decode($response, true);
    }
    
}
