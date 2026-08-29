<?php

namespace app\controllers\api\v1;

use yii\data\ArrayDataProvider;
use app\components\RestController;
use app\components\CookieAuth;

class WhoAmIController extends RestController
{
	
    public $modelClass = '\app\models\User';
    
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CookieAuth::className(),
        ];
        unset($behaviors['access']);
        
        return $behaviors;
    }

    public function actions()
	{
		$actions = parent::actions();
        unset(
            $actions['index'],
            $actions['create'],
            $actions['update'],
            $actions['delete'],
        );

		return $actions;
	}
    
    public function actionIndex($client='')
    {
        $identity = \Yii::$app->user->identity;
        
        if ($identity === null) {
            throw new \yii\web\UnauthorizedHttpException('Not authenticated');
        }

        //$ou = \app\models\OrganizationalUnitResource::findOne(\Yii::$app->session->get('organizational_unit_id'));
        
        $ou = \app\models\OrganizationalUnitResource::findOne($identity->getCurrentChosenOrganizationalUnit()->id);
        
        $identity->preferences = JSON_encode(['user_agent'=>$_SERVER['HTTP_USER_AGENT']??'unknown user agent']); // it's not a real preference, just to store it in the log

        \app\components\LogHelper::log("PWA Access ($client)", $identity, ['excluded'=>[
                'id', 'username','first_name', 'last_name', 'email', 'phone', 'auth_key', 'access_token', 'otp_secret', 'password_hash', 'status', 'external_id', 'last_renewal', 'created_at', 'updated_at', 'last_action_at',
            ]]);

        return [
            'username'=>$identity->username,
            'first_name'=>$identity->first_name,
            'last_name'=>$identity->last_name,
            'organizational_unit'=>$ou,
            //'debug_ou_from_session' => \Yii::$app->session->get('organizational_unit_id', '??'),
            'secret' => \Yii::$app->params['receipts']['pwa']['offline_secret'],
            'client' => $client,
        ];
    }
}
