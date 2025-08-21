<?php

namespace app\models\sm;

use Yii;
use yii\data\ArrayDataProvider;
use yii\web\UploadedFile;

/**
 * This is the model class for accessing Mastodon services.
 */

class MastodonSchedulerClient
{
    private $params;
    
    public function __construct($params)
    {
        /* $params should be an array with the following keys:
            'user' => 'user@mastodon.example',
            'service' => 'https://...example.com/api',
        */

        $this->params = $params;
    }
    
    public function getScheduledPosts()
    {
        $data = $this->_send('/');
        return new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'attributes' => ['id', 'scheduled_at'],
            ],
        ]);
    }

    public function delete($id)
    {
        $info = $this->_send('/?id=' . $id, null, 'DELETE');
        return $info['result']=='ok';
    }
    
    public function schedule($at, $status, $image, $description='')
    {
        $attachments = [];
        
        if ($image) {
            $attachments[] = [
                'filename' => $image->baseName . '.' . $image->extension,
                'data' => base64_encode(file_get_contents($image->tempName)),
                'description' => $description,
            ];
            
        };
        
        $info = $this->_send('/?at='.$at, ['text'=>$status, 'attachments'=>$attachments], 'POST');
        return $info['result']=='ok';
    }
    
    private function _send($path, $contents = [], $method='GET')
    {
        $http_options = [
            'method'=>$method,
            'ignore_errors' => true,
        ];

        $headers = [
            'Content-type: application/json',
        ];
        
        $headers[] = 'X-Mastodon-User: ' . $this->params['user'];

        if ($contents) {
            $data = json_encode($contents);
            $headers[] = 'Content-Length: ' . strlen($data);
            $http_options['content']  = $data;
        }
        
        $http_options['header'] = implode("\r\n", $headers);
                
        try {
            $response = file_get_contents($this->params['service'] . $path, false, stream_context_create([
                'http'=> $http_options,
            ]));
        }
        catch (Exception $e) {
            die('no internet connection');
        }
        
        return json_decode($response, true);
    }
    
}
