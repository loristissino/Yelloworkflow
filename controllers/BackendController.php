<?php

namespace app\controllers;

use Yii;
use app\components\CController;
use yii\web\Response;
use app\models\User;
use app\models\Notification;
use app\models\NotificationTemplate;
use yii\helpers\Markdown;

class BackendController extends CController
{
    public function actionIndex() // Shows the back end page
    {
        return $this->render('index');
    }
    
    public function actionMarkdownDocumentation() // Generates a markdown file with the current list of accounts, transaction templates, etc.
    {
        $this->layout = false;
        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'text/plain; charset=utf-8');
        return $this->render('md_documentation');
    }
    
    public function actionProjectWorkflow($seed=1, $gender='male', $static=true)
    {
        $seed = $static ? $seed: $this->_computeSeed($seed, 'project', $gender);
        return $this->_workflowRepresentation(new \app\models\Project(), $seed, $gender);
    }

    public function actionPeriodicalReportWorkflow($seed=1, $gender='male', $static=true)
    {
        $seed = $static ? $seed: $this->_computeSeed($seed, 'periodical-report', $gender);
        return $this->_workflowRepresentation(new \app\models\PeriodicalReport(), $seed, $gender);
    }

    public function actionTransactionWorkflow($seed=1, $gender='female', $static=true)
    {
        $seed = $static ? $seed: $this->_computeSeed($seed, 'transaction', $gender);
        return $this->_workflowRepresentation(new \app\models\Transaction(), $seed, $gender);
    }
    
    public function actionDigitalReceiptWorkflow($seed=1, $gender='female', $static=true)
    {
        $seed = $static ? $seed: $this->_computeSeed($seed, 'digital-receipt', $gender);
        return $this->_workflowRepresentation(new \app\models\DigitalReceipt(), $seed, $gender);
    }

    public function actionSetSeed($seed=1)
    {
        Yii::$app->session->set('seed', $seed);
        Yii::$app->session->setFlash('success', Yii::t('app', 'Seed set to {value}.', ['value'=>$seed]));
        return $this->redirect(['index']);
    }

    public function actionUnsetSeed()
    {
        Yii::$app->session->set('seed', null);
        Yii::$app->session->setFlash('success', Yii::t('app', 'Seed unset.'));
        return $this->redirect(['index']);
    }
    
    public function actionAttachments($pagesize=500)
    {
        $searchModel = new \app\models\AttachmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = [
            'pageSize' => $pagesize,
        ];

        return $this->render('attachments', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionTestNotification($id, $return_url)
    {
        $user = User::findOne($id);

        if (!$user){
            Yii::$app->session->setFlash('error', Yii::t('app', 'User not found'));
            return $this->redirect($return_url);
        }
        
        $code = 'Backend/test';
        $notificationTemplate = NotificationTemplate::find()->withCode($code)->one();
        if (!$notificationTemplate) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Template not found: {code}', ['code'=>$code]));
            return $this->redirect($return_url);
        }
        
        $notification = new Notification();
        foreach([
            'subject',
            'plaintext_body',
        ] as $attribute) {
            $notification->$attribute = $notificationTemplate->$attribute;
        }
        $notification->html_body = Markdown::process($notificationTemplate->md_body);
        $notification->user_id = $user->id;
        if ($notification->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Test notification saved.'));
            return $this->redirect($return_url);
        }
        Yii::$app->session->setFlash('error', Yii::t('app', 'Something went wrong.'));
        return $this->redirect($return_url);
    }
    
    private function _workflowRepresentation($model, $seed, $gender)
    {
        return $this->render('workflow_representation', [
            'model' => $model,
            'seed' => $seed,
            'gender' => $gender,
        ]);
    }
    
    private function _computeSeed($seed, $workflow, $gender) 
    {
        $seed = Yii::$app->session->get('seed', $seed);
        Yii::$app->response->headers->add('Refresh', '3; url=' . Yii::$app->urlManager->createAbsoluteUrl(["backend/$workflow-workflow", 'seed'=>$seed, 'gender'=>$gender, 'static'=>0]));
        Yii::$app->session->set('seed', $seed+1);
        return $seed;
    }
}
