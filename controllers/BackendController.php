<?php

namespace app\controllers;

use Yii;
use app\components\CController;
use yii\web\Response;

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
    
    public function actionProjectWorkflow($seed=1)
    {
        return $this->_workflowRepresentation(new \app\models\Project(), $seed);
    }

    public function actionPeriodicalReportWorkflow($seed=1)
    {
        return $this->_workflowRepresentation(new \app\models\PeriodicalReport(), $seed);
    }

    public function actionTransactionWorkflow($seed=1)
    {
        return $this->_workflowRepresentation(new \app\models\Transaction(), $seed);
    }

    private function _workflowRepresentation($model, $seed)
    {
        return $this->render('workflow_representation', [
            'model' => $model,
            'seed' => $seed,
        ]);
    }

}
