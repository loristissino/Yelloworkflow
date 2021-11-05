<?php

namespace app\controllers;

use Yii;
use app\models\Notification;
use app\models\NotificationSearch;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\MethodNotAllowedHttpException;
use app\components\CController;

/**
 * NotificationsController implements the CRUD actions for Notification model.
 */
class NotificationsController extends CController
{
    /**
     * Lists all Notification models.
     * @return mixed
     */
    public function actionIndex($seen=null, $pagesize=20) // Lists the notifications for the logged-in user
    {
        $seen = $seen === 'true' ? true : false;
        
        $searchModel = new NotificationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Notification::find()->seen($seen)->forUser(Yii::$app->user->identity->id));
        
        $dataProvider->sort->defaultOrder = ['created_at' => SORT_DESC];
        
        $dataProvider->pagination = [
            'pageSize' => $pagesize,
        ];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'seen' => $seen,
        ]);
    }

    /**
     * Displays a single Notification model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) // Displays a specific notification, givent its id
    {
        $model = $this->findModel($id);
        if (!$model->seen_at) {
            $model->markSeen();
        }
        return $this->render('view', [
            'model' => $model,
        ]);
    }
    
    public function actionSend($key) // Sends ready notifications via email
    {
        $this->enableCsrfValidation = false;
        if ($key!=Yii::$app->params['notificationsKey'])
        {
            throw new ForbiddenHttpException(Yii::t('app', 'Not authorized.'));
        }
        /*
         * Not possible because of CSRF token required
         * FIXME: move this to REST API requests
        if (!Yii::$app->request->isPost)
        {
            throw new MethodNotAllowedHttpException(Yii::t('app', 'Method not allowed.'));
        }
        */
        $notifications = Notification::find()->sent(false)->orderBy(['created_at' => SORT_ASC])->all();
        $this->layout = false;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $data = ['notifications' => [] ];
        
        foreach($notifications as $notification) {
            $status = $notification->sendEmail() ? 'sent': 'not sent';
            $data['notifications'][] = [
                'notification_id' => $notification->id,
                'status' => $status,
            ];
            sleep(1); // let's wait one second between each email...
        }
        return $this->renderContent($data);
    }

    public function beforeAction($action)
	{
		// see https://www.yiiframework.com/doc/api/2.0/yii-web-controller#beforeAction()-detail
		$this->modelClass = Notification::className();

		if (!parent::beforeAction($action)) {
			return false;
		}
		
		// other custom Code here

		return true; // or false to not run the action
	}

    /**
     * Finds the Notification model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Notification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Notification::find()->withId($id)->forUser(Yii::$app->user->identity->id)->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
