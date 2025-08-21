<?php

namespace app\components;

use Yii;
use yii\mail\BaseMailer;
use yii\base\Component;

class Mailer extends Component
{
    public $useFileTransport = false;
        
    private $_transport;
    
    private $_data;
    
    public function setTransport($transport)
    {
        if (!is_array($transport) && !is_object($transport)) {
            throw new InvalidConfigException('"' . get_class($this) . '::transport" should be either object or array, "' . gettype($transport) . '" given.');
        }
        $this->_transport = $transport;
    }

    public function compose($view = null, array $params = [])
    {
        $this->_data['isHTML'] = false;
        $this->_data['apikey'] = $this->_transport['apikey'];
        return $this;
    }
    
    public function setApikey($apikey)
    {
        // to use a different apikey in specific cases
        // to be called after compose()
        $this->_data['apikey'] = $apikey;
        return $this;
    }

    public function setFrom($email)
    {
        // not actually used, here just for compatibility
        $this->_data['from'] = $email;
        return $this;
    }

    public function setTo($email, $name='')
    {
        $this->_data['recipientEmail'] = $email;
        $this->_data['recipientName'] = $name;
        return $this;
    }

    public function setSubject($subject)
    {
        $this->_data['subject'] = '=?UTF-8?B?'.base64_encode($subject).'?=';
        return $this;
    }

    public function setHtmlBody($text)
    {
        $this->_data['body'] = $text;
        $this->_data['isHTML'] = true;
        return $this;
    }

    public function setTextBody($text)
    {
        $this->_data['body'] = $text;
        $this->_data['isHTML'] = false;
        return $this;
    }
    
    public function send()
    {
        return $this->sendMessage();
    }
    
    protected function sendMessage() {

        $payload = json_encode($this->_data);

        $result = file_get_contents(
            $this->_transport['emailService'],
            false,
            stream_context_create([
                'http'=>[
                    'method'=>"POST",
                    'header'=>"Content-Type: application/json\r\n" .
                              "Content-Length: " . strlen($payload) . "\r\n",
                    'content'=>$payload,
                ]
            ])
        );

        $r=json_decode($result);
        return $r->status=='OK';
    }
}
