<?php

namespace app\controllers;

use Yii;
use app\models\PeriodicalReport;
use app\models\PeriodicalReportSearch;
use yii\web\NotFoundHttpException;
use app\components\CController;
use app\models\PeriodicalReportsBulkCreationForm;
use app\models\TransactionStatusesForm;
use yii\helpers\Url;

/**
 * PeriodicalReportsManagementController implements the CRUD actions for PeriodicalReport model.
 */
class PeriodicalReportsManagementController extends CController
{

    public function init()
    {
        parent::init();
        $this->viewPath = '@app/views/periodical-reports';
        // This is needed because we want to use the same views for both
        // submitter and manager, that use different controllers
    }

    /**
     * Lists all PeriodicalReport models.
     * @return mixed
     */
    public function actionIndex($active=null, $pagesize=100) // Lists all periodical reports 
    {        
        $activeStatus = $active == 'false' ? false : true;
        $active = $activeStatus ? 'true': 'false';
        
        $searchModel = new PeriodicalReportSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, PeriodicalReport::find()->active($activeStatus));

        $dataProvider->sort->defaultOrder = ['end_date' => SORT_DESC, 'begin_date' => SORT_DESC];
        
        $dataProvider->pagination = [
            'pageSize' => $pagesize,
        ];

        return $this->render('management-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PeriodicalReport model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) // Displays a periodical report, given its id
    {
        return $this->render('/periodical-reports/view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PeriodicalReport model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() // Creates a single periodical report
    {
        $model = new PeriodicalReport();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionCreateReports() // Creates a set of periodical reports
    {
        $model = new PeriodicalReportsBulkCreationForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create-reports', [
            'model' => $model,
        ]);
    }

    public function actionSummary($type='balances', $before=null)
    {
        if (!$before) {
            $before = '2100-01-01';
        }
        $model = new TransactionStatusesForm();
        $model->loadValuesFromUserSettings();
        
        $data = PeriodicalReport::getSummaryData($type, $model->weight, $before);
        
        $model->url = Url::to(['summary', ['type'=>'balances']]);

        return $this->render('summary-'.$type, [
            'dataProvider' => $data['provider'],
            'fields' => $data['fields'],
            'enforcedBalances' => $data['enforcedBalances'],
            'model' => $model,
            'before'=>$before,
        ]);
    }

    public function actionRecap($type='general', $ou=null)
    {
        $data = PeriodicalReport::getRecapData($type, $ou);

        return $this->render('summary-recap-' . $type, [
            'dataProvider' => $data['provider'],
            'fields' => $data['fields'],
        ]);
    }

    public function beforeAction($action)
    {
        // see https://www.yiiframework.com/doc/api/2.0/yii-web-controller#beforeAction()-detail
        $this->modelClass = PeriodicalReport::className();

        if (!parent::beforeAction($action)) {
            return false;
        }
        
        // other custom Code here

        return true; // or false to not run the action
    }
    
    public function notSavingBulkActions() {
        return []; 
    }       

    public function actionChange($id, $status) // Changes the workflow status of a periodical report
    {
        $model = $this->findModel($id, false);
        return $this->_changeWorkflowStatus($model, $status);
    }

    /**
     * Finds the PeriodicalReport model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PeriodicalReport the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PeriodicalReport::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
