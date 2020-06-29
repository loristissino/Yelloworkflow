<?php

namespace app\components;

use yii\web\User;
use yii\helpers\ArrayHelper;
use app\models\Authorization;

class CUser extends User
{
    private $_authorizationCache = [];

    public function hasAuthorizationFor($controller_id, $action_id='*', $method='*')
    {
        if (!is_array($controller_id)) {
            $tokens = explode('/', $controller_id);
            
            if (sizeof($tokens)>1) {
                $controller_id = $tokens;
            }
        }
        
        if (is_array($controller_id)) {
            list($controller_id, $action_id, $method) = array_replace(['', '*', '*'], $controller_id);
        }
        
        $key = $controller_id . '/' . $action_id;

        $value = ArrayHelper::getValue($this->_authorizationCache, $key, null);
        
        if (is_null($value)) {
            $result = $this->getAuthorizationsFor($controller_id, $action_id);
                
            $value = sizeof($result)>0;
    
            ArrayHelper::setValue($this->_authorizationCache, $key, $value);
        }
        
        return $value;
    }
    
    public function getAuthorizationsFor($controller_id, $action_id='*', $method='*')
    {
        return Authorization::find()
            ->active()
            ->withController($controller_id)
            ->withAction($action_id)
            ->requestedBy($this)
            ->all();
    }
        
}
