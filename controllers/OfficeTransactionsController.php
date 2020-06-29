<?php

namespace app\controllers;

use Yii;
use app\models\Transaction;
use app\models\TransactionForm;
use app\models\TransactionSearch;
use app\models\TransactionTemplate;
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

    public function actionIndex($active=null)
    {
        $active = $active == 'false' ? false : true;
        $searchModel = new TransactionSearch();
        $dataProvider = $searchModel->search(
            Yii::$app->request->queryParams, 
            Transaction::find()->active($active)->withOneOfStatuses(['TransactionWorkflow/prepared', 'TransactionWorkflow/notified'])
        );
        $dataProvider->sort->defaultOrder = ['date' => SORT_DESC];
        
        return $this->render('/transactions/office-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Transaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($organizational_unit_id=null, $project_id=null, $amount=null)
    {
        $model = new TransactionForm();
        $model->begin_date = date('Y-m-d', mktime(0, 0, 0, 1, 1, date('Y')));
        $model->end_date = date('Y-m-d', mktime(0, 0, 0, 12, 31, date('Y')));
        $model->templates = TransactionTemplate::getActiveOfficeTransactionTemplatesAsArray(); // office only

        if ($model->load(Yii::$app->request->post()) && $model->setPeriodicalReport() && $model->save()) {
            $model->transaction->sendToStatus('prepared');
            $model->transaction->save();
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
            }
        }

        return $this->render('/transactions/create', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Transaction model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('/transactions/view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionDelete($id)
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


    public function actionChange($id, $status)
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
