<?php

namespace app\controllers;

use Yii;
use app\models\PeriodicalReport;
use app\models\PeriodicalReportForm;
use app\models\PeriodicalReportSearch;
use app\models\Transaction;
use yii\web\NotFoundHttpException;
use app\components\CController;
use app\models\PeriodicalReportsBulkCreationForm;

/**
 * PeriodicalReportsSubmissionsController implements the CRUD actions for PeriodicalReport model.
 */
class PeriodicalReportSubmissionsController extends CController
{
    use SubmissionsTrait;

    public function init()
    {
        parent::init();
        $this->viewPath = '@app/views';
        // This is needed because we want to use the same views for both
        // submitter and manager, that use different controllers
    }

    public function beforeAction($action)
    {
        $this->modelClass = PeriodicalReport::className();
        
        $this->setOrganizationalUnit($action);
        if (!$this->organizationalUnit) {
            $this->redirect(['/site/choose-organizational-unit', 'return'=>\Yii::$app->request->url]);
            return;
        }
        
        return parent::beforeAction($action);
    }

    /**
     * Lists all PeriodicalReport models.
     * @return mixed
     */
    public function actionIndex($active=null, $pagesize=100) // List all periodical reports for the organizational unit of the logged-in user
    {        
        $active = $active == 'false' ? false : true;
        
        $searchModel = new PeriodicalReportSearch();
        $dataProvider = $searchModel->search(
            Yii::$app->request->queryParams,
            PeriodicalReport::find()->withOrganizationalUnitId($this->organizationalUnit->id)->active($active)
            );

        $dataProvider->sort->defaultOrder = ['end_date' => SORT_DESC, 'begin_date' => SORT_DESC];
        
        $dataProvider->pagination = [
            'pageSize' => $pagesize,
        ];

        return $this->render('periodical-reports/submissions-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'organizationalUnit' => $this->organizationalUnit,
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
        $model = $this->findModel($id);
        return $this->render('/periodical-reports/view', [
            'model' => $model,
        ]);
    }

    /**
     * Shows a printable version of a single PeriodicalReport model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionPrint($id) // Shows a printable version of a periodical report, given its id
    {
        $this->layout = 'print';
        $periodicalReport = $this->findModel($id);
        return $this->render('/periodical-reports/print', [
            'model' => $periodicalReport,
            'beginningBalanceDataProvider' => Transaction::getBalance($periodicalReport, false, ['TransactionWorkflow/recorded', 'TransactionWorkflow/submitted', 'TransactionWorkflow/reimbursed']),
            'endBalanceDataProvider' => Transaction::getBalance($periodicalReport, true, ['TransactionWorkflow/recorded', 'TransactionWorkflow/submitted', 'TransactionWorkflow/reimbursed']),
        ]);
    }

    public function actionUpdate($id) // Updates a periodical report (only for attachments)
    {
        $model = $this->findModel($id);
        
        if (! $model->isOwnedByCurrentUser)
        {
            throw new ForbiddenHttpException(Yii::t('app', 'Not authorized.'));
        }
        
        echo "<pre>";
        
        print_r($_FILES);
        
        print_r(Yii::$app->request->post());
        
        if ($model->load(Yii::$app->request->post()) && $model->saveAttachments()) {
            return $this->redirect(['/periodical-report-submissions/view', 'id'=>$id]);
        }
        else {
            die("Very uncommon");
        }
        
        throw new NotFoundHttpException(Yii::t('app', 'This action can\'t be called directly.'));
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
        $model = PeriodicalReport::find()->withId($id)->withOrganizationalUnitId($this->organizationalUnit->id)->one();
        
        if ($model) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
