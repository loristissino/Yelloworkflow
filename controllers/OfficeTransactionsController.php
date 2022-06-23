<?php

namespace app\controllers;

use Yii;
use app\models\Transaction;
use app\models\TransactionForm;
use app\models\TransactionSearch;
use app\models\TransactionTemplate;
use app\models\Project;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use app\components\CController;

/**
 * OfficeTransactionsController implements the CRUD actions for Transaction model.
 */
class OfficeTransactionsController extends CController
{

    public $periodicalReport;
    
    public function init()
    {
        parent::init();
        $this->viewPath = '@app/views';
        // This is needed because we want to use the same views for both
        // submitter and manager, that use different controllers
    }

    public function beforeAction($action)
	{
		$this->modelClass = Transaction::className();

		if (!parent::beforeAction($action)) {
			return false;
		}

		return true; // or false to not run the action
	}

    public function actionIndex($active=null, $recorded=null) // Lists all transactions prepared by office workers
    {
        $active = $active == 'false' ? false : true;

        $statuses = ['TransactionWorkflow/prepared', 'TransactionWorkflow/notified'];
        
        if ($recorded=='true') {
            $statuses = ['TransactionWorkflow/recorded', 'TransactionWorkflow/reimbursed', 'TransactionWorkflow/archived'];
        }
        
        $searchModel = new TransactionSearch();
        $dataProvider = $searchModel->search(
            Yii::$app->request->queryParams, 
            Transaction::find()->active($active)->withOneOfStatuses($statuses)
        );
        $dataProvider->sort->defaultOrder = ['date' => SORT_DESC];
        
        return $this->render('/transactions/office-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'recorded'=>$recorded=='true',
        ]);
    }

    /**
     * Creates a new Transaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($organizational_unit_id=null, $project_id=null, $amount=null) // Creates a transaction [office workers]
    {
        $model = new TransactionForm();
        $model->begin_date = date('Y-m-d', mktime(0, 0, 0, 1, 1, date('Y')-1));
        $model->end_date = date('Y-m-d', mktime(0, 0, 0, 12, 31, date('Y')+1));
        $model->templates = TransactionTemplate::getActiveOfficeTransactionTemplatesAsArray(); // office only

        if ($model->load(Yii::$app->request->post()) && $model->setPeriodicalReport() && $model->save()) {
            $model->transaction->sendToStatus('prepared');
            $model->transaction->save();

            if ($model->immediateNotification) {
                $model->transaction->sendToStatus('notified');
                $model->transaction->save(false);
            }

            if ($project_id) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Transaction successfully prepared.'));
                return $this->redirect(['projects-management/view', 'id'=>$project_id]);
            }
            return $this->redirect(['/office-transactions/index']);
        }

        $model->date = date('Y-m-d');
        $model->organizational_unit_id = $organizational_unit_id;
        $model->amount = $amount;
        
        if($project_id) {
            $project = \app\models\Project::findOne($project_id);
            if ($project) {
                $model->description = Yii::t('app', 'Reimbursement for project «{title}»', ['title'=>$project->title]);
                $model->project_id = $project->id;
            }
        }

        return $this->render('/transactions/create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Transaction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) // Updates a transaction
    {
        $transaction = $this->findModel($id);
        
        if (! $transaction->getCanBeUpdated('prepared'))
        {
            throw new ForbiddenHttpException(Yii::t('app', 'Not updatable in this state.'));
        }
        
        $model = new TransactionForm();
        $model->importDataFromTransaction($transaction);
        $model->begin_date = date('Y-m-d', mktime(0, 0, 0, 1, 1, date('Y')));
        $model->end_date = date('Y-m-d', mktime(0, 0, 0, 12, 31, date('Y')));
        
        $model->templates = TransactionTemplate::getActiveTransactionTemplatesAsArray();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($model->immediateNotification) {
                $model->transaction->sendToStatus('notified');
                $model->transaction->save(false);
            }
            return $this->redirect(['/office-transactions/view', 'id'=>$id]);
        }        
        
        return $this->render('/transactions/update', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Transaction model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) // Displays a transaction, given its id
    {
        return $this->render('/transactions/view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionDelete($id) // Deletes a transaction, given its id [only if prepared by office workers]
    {
        $model = $this->findModel($id);
        
        if ( $model->getWorkflowStatus()->getId() != 'TransactionWorkflow/prepared' )
        {
            throw new ForbiddenHttpException(Yii::t('app', 'Not deletable in this state.'));
        }

        $model->delete();
        Yii::$app->session->setFlash('success', Yii::t('app', 'Item successfully deleted.'));
        
        return $this->redirect(['office-transactions/index']);
    }
    
    public function actionProjects($organizational_unit_id) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return Project::getProjectsAsArray(['created_at' => SORT_DESC], $organizational_unit_id, true);
    }

    public function actionChange($id, $status) // Changes the workflow status of a transaction
    {
        $model = $this->findModel($id, false);
        return $this->_changeWorkflowStatus($model, $status);
    }

    public function actionViewOuAccountingSummary($id)
    {
        $ou = \app\models\OrganizationalUnit::findOne($id);
        if (!$ou){
            return '';
        }
        return Yii::t('app', 'Ceiling Amount') . ': <strong>'. $ou->getFormattedCeilingAmount() . '</strong><br />' . $ou->getSignificantLedgers();
    }

    /**
     * Finds the Transaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Transaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Transaction::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    
    protected function findPeriodicalReport($id)
    {
        if (($this->periodicalReport = \app\models\PeriodicalReport::find()
            ->withId($id)->withOrganizationalUnitId(Yii::$app->session->get('organizational_unit_id'))
            ->draft(true)
            ->one()) !== null) {
            return $this->periodicalReport;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    
}
