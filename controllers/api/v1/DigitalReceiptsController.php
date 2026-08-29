<?php

namespace app\controllers\api\v1;

use yii\data\ActiveDataProvider;
use app\components\RestController;
use app\components\CookieAuth;
use app\models\DigitalReceiptResource;

class DigitalReceiptsController extends RestController
{
	public $modelClass = '\app\models\DigitalReceiptResource';
	
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
            $actions['create'],
            $actions['update'],
            $actions['delete']
        );

		return $actions;
	}
        
    
	public function prepareDataProvider()
	{
		$query = DigitalReceiptResource::find()->withOrganizationalUnitId(\Yii::$app->session->get('organizational_unit_id'));

		$provider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => 1000,
			],
			'sort' => [
				'defaultOrder' => [
					'created_at' => SORT_DESC, 
				]
			],
		]);
        
		return $provider;
	}
    
    /*
    public function actionCreate()
    {
        $this->checkAuth('digital-receipts');
        $data = \Yii::$app->request->bodyParams;

        $receipt = DigitalReceiptResource::create(
            $data
        );
        
        \Yii::$app->response->statusCode = 201;
        return [
            'success' => true,
            'id' => $receipt->id,
            'client_id' => $receipt->client_id,
            'status' => $receipt->wf_status,
        ];
    }
    */
    
    public function actionCreate()
    {
        $this->checkAuth('digital-receipts');

        $data = \Yii::$app->request->bodyParams;

        $result = DigitalReceiptResource::create($data);

        $receipt = $result['receipt'];

        \Yii::$app->response->statusCode =
            $result['created'] ? 201 : 200;

        return [
            'success' => true,
            'id' => $receipt->id,
            'client_id' => $receipt->client_id,
            'status' => $receipt->wf_status,
        ];
    }    
    
}
