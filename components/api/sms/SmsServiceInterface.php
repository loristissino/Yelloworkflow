<?php

namespace app\components\api\sms;

use Yii;

interface SmsServiceInterface
{
    public function send($recipient, $message, $options=[]);
}
