<?php

namespace app\controllers\api\v1;

use yii\data\ActiveDataProvider;
use app\components\RestController;
use app\models\TransactionResource;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\ServerErrorHttpException;

class TransactionsController extends RestController
{
	public $modelClass = '\app\models\TransactionResource';

	public function actions()
	{
		$actions = parent::actions();
        
        unset($actions['create']);

		return $actions;
	}

	public function prepareDataProvider()
	{
		$query = TransactionResource::find();

		$provider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => 1000,
			],
			'sort' => [
				'defaultOrder' => [
					'id' => SORT_ASC, 
				]
			],
		]);
		
		return $provider;
	}
    
    public function actionCreate()
    {
        // TODO
        
        /*
        * \Yii::$app->user is an instance of \app\components\CUser
        * \Yii::$app->user->identity is an instance of \app\models\User
        
        /*
         * use TransactionForm for saving data
         * or use transaction for everything
         * put everything in a try catch
         * check about wf_status --> set directly to what wanted
         * find periodical report by date but in draft status
         */

        
        $data = \Yii::$app->getRequest()->getBodyParams();  // the request must have a Content-Type: application/json header
        
        $this->checkAuth(ArrayHelper::getValue($data, 'type', '') . '-transactions', '*');

        $saved = false;
        
        $model = new \app\models\TransactionForm();
        
        try {
            $date = ArrayHelper::getValue($data, 'date', date('Y-m-d'));
            $model->setPeriodicalReport($date, ArrayHelper::getValue($data, 'organizational_unit_id', 0));
            
            $model->importSettings([
                'transaction_template_id' =>  ArrayHelper::getValue($data, 'transaction_template_id', 0),
                'date' => $date,
                'description' => ArrayHelper::getValue($data, 'description', ''),
                'notes' => ArrayHelper::getValue($data, 'notes', ''),
                'amount' => ArrayHelper::getValue($data, 'amount', 0),
            ]);
            $saved = $model->save();
            if($saved) {
                $model->transaction->sendToStatus('prepared');
                $model->transaction->save();
            }
            
        }
        catch (Exception $e) {
            $model->addError('request', $e->getMessage());
        }

        if ($saved) {
            $response = \Yii::$app->getResponse();
            $response->setStatusCode(201);
            $transaction = $model->transaction;
            $response->getHeaders()->set('Location', Url::to(['transactions/view', 'id' => $transaction->id], true));
            return $transaction;
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        
        return $model;
    }
}


