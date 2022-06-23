<?php

namespace app\components;

use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpHeaderAuth;
use app\controllers\ControllerTrait;
use yii\web\ForbiddenHttpException;


class RestController extends ActiveController
{
    use ControllerTrait;

	public function init()
	{
		parent::init();
		\Yii::$app->user->enableSession = false;
	}

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpHeaderAuth::className(),
		];
        $behaviors['access'] = [
            'class' => \app\components\AuthorizationFilter::className(),
        ];
        return $behaviors;
    }
    
	public function beforeAction($action)
	{
		\Yii::$app->language = 'en';

		if (!parent::beforeAction($action)) {
			return false;
		}
		return true;
	}

	public function actions()
	{
		$actions = parent::actions();

		// customize the data provider preparation with the "prepareDataProvider()" method
		$actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

		return $actions;
	}
    
    public function checkAuth($controller_id, $action_id='*', $method='*')
    {
        $auths = \Yii::$app->user->getAuthorizationsFor($controller_id, $action_id, $method);
        if (sizeof($auths)>0)
        {
            $this->authorization_ids = \Yii\helpers\ArrayHelper::map($auths, 'id', 'identifier');
            return true;
        }
        throw new ForbiddenHttpException(\Yii::t('app', 'Not authorized.'));
    }
	
}
