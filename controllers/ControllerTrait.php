<?php

namespace app\controllers;

use Yii;

trait ControllerTrait
{
    public $authorization_ids = [-1];
        
    public function getAuthorizationId()
    {
        $values = array_keys($this->authorization_ids);
        return array_shift($values);
    }

}

