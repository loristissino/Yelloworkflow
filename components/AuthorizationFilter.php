<?php

namespace app\components;

use Yii;
use yii\base\ActionFilter;
use yii\web\ForbiddenHttpException;
use app\models\Authorization;
use yii\helpers\ArrayHelper;

class AuthorizationFilter extends ActionFilter
{    
    public function beforeAction($action)
    {       
        // TODO use CUser method for this call
        $result = Authorization::find()
            ->active()
            ->withController($action->controller->id)
            ->withActionAndMethod($action->id, Yii::$app->request->method)
            ->requestedBy(Yii::$app->user)
            ->all();
            
        if (sizeof($result)==0)
        {
            if (Yii::$app->user->isGuest) {
                $action->controller->redirect(['/site/login', 'return'=>Yii::$app->request->url]);
            }
            else {
                throw new ForbiddenHttpException(Yii::t('app', 'Not authorized.'));
            }
        }
        else {
            $action->controller->authorization_ids = ArrayHelper::map($result, 'id', 'identifier');
        }
        
        return parent::beforeAction($action);
    }

    public function afterAction($action, $result)
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->user->identity->touchLastActionAt();
        }
        return parent::afterAction($action, $result);
    }

}

