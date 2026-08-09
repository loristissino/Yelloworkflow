<?php

namespace app\controllers;

use Yii;
use app\models\Transaction;
use app\models\TransactionSearch;
use app\models\TransactionTemplate;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use app\components\CController;

/**
 * FastTransactionsController implements the CRUD actions for Transaction model.
 */
class FastTransactionsController extends CController
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

    public function actionIndex($active=null) // Lists all transactions prepared by office workers
    {
        $active = $active == 'false' ? false : true;
        
        $statuses = ['TransactionWorkflow/sealed', 'TransactionWorkflow/handled', 'TransactionWorkflow/rejected'];
        
        if (Yii::$app->session->get('include_generated_transactions', false)) {
            $statuses[] = 'TransactionWorkflow/generated';
        }
        
        $searchModel = new TransactionSearch();
        $dataProvider = $searchModel->search(
            Yii::$app->request->queryParams, 
            Transaction::find()->active($active)->withOneOfStatuses($statuses)
        );
        $dataProvider->sort->defaultOrder = ['date' => SORT_DESC, 'created_at' => SORT_DESC];
        
        return $this->render('/transactions/office-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionToggleGeneratedTransactionsInclusion()
    {
        Yii::$app->session->set('include_generated_transactions', !Yii::$app->session->get('include_generated_transactions', false));
        return $this->redirect('index');
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

    public function actionUpdate($id) // Updates a transaction
    {
        $model = $this->findModel($id);
        
        $model->scenario = Transaction::SCENARIO_HANDLING;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id'=>$id]);
        }        
        
        return $this->render('transactions/handling_update', [
            'model' => $model,
        ]);
    }

    public function actionChange($id, $status) // Changes the workflow status of a transaction
    {
        $model = $this->findModel($id, false);
        return $this->_changeWorkflowStatus($model, $status);
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
