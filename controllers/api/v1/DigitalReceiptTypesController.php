<?php

namespace app\controllers\api\v1;

use yii\data\ActiveDataProvider;
use app\components\RestController;
use app\components\CookieAuth;
use app\models\DigitalReceiptTypeResource;

class DigitalReceiptTypesController extends RestController
{
	public $modelClass = '\app\models\DigitalReceiptTypeResource';
	
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CookieAuth::className(),
        ];
        unset($behaviors['access']);
        
        return $behaviors;
    }    
    
	public function prepareDataProvider()
	{
		$query = DigitalReceiptTypeResource::find()->active();

		$provider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => 1000,
			],
			'sort' => [
				'defaultOrder' => [
					'title' => SORT_DESC, 
				]
			],
		]);
        
		return $provider;
	}
}
