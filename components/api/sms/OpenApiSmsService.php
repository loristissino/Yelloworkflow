<?php

namespace app\components\api\sms;

use Yii;
use yii\base\Component;
use app\components\api\sms\SmsServiceInterface;

/**
 * OpenApiSmsService
 */
class OpenApiSmsService extends Component implements SmsServiceInterface
{
    private static $urls=[
        'prod'=>'https://sms.openapi.com',
    ];
    
    public $token;
    public $callback;
    public $sender;
    
    public function send($recipient, $message, $options=[])
    {
        $url = self::$urls['prod'];
        
        $data = [
            'recipient' => $recipient,
            'sender' => $this->sender,
            'message' => $message,
        ];
        
        if ($this->callback) {
            $data['callback'] = [
                'url'=>$this->callback,
            ];
        }
        
        $payload=json_encode($data);

        $result = false;
        
        $result = @file_get_contents(
            $url . '/WW-messages',
            false,
            stream_context_create([
                'http'=>[
                    'method'=>"POST",
                    'ignore_errors' => true,
                    'header'=>"Content-Type: application/json\r\n" .
                              "Content-Length: " . strlen($payload) . "\r\n" .
                              "Authorization: Bearer " . $this->token . "\r\n",
                    'content'=>$payload,
                ]
            ])
        );
        
        if ($result===false) {
            file_put_contents('failed_sms_'. time(). '.log', $payload);
            return false;
        }
        file_put_contents('ok_sms_'. time(). '.log', $payload);
        return true;
    }

}
