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
 * TransactionSubmissionsController implements the CRUD actions for Transaction model.
 */
class TransactionSubmissionsController extends CController
{
    
    use SubmissionsTrait;

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

    /**
     * Displays a single Transaction model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) // Displays a transaction
    {
        return $this->render('/transactions/view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Transaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($periodical_report) // Creates a transaction for a specific periodical report
    {
        $this->periodicalReport = $this->findPeriodicalReport($periodical_report, true);

        $model = new TransactionForm();
        $model->periodicalReport = $this->periodicalReport;
        $model->begin_date = $this->periodicalReport->begin_date;
        $model->end_date = $this->periodicalReport->end_date;
        
        $model->templates = TransactionTemplate::getActiveTransactionTemplatesAsArray();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/transaction-submissions/view', 'id'=>$model->id]);
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
        
        $this->periodicalReport = $transaction->periodicalReport;
        
        if (! $this->periodicalReport->isOwnedByCurrentUser)
        {
            throw new ForbiddenHttpException(Yii::t('app', 'Not authorized.'));
        }

        if (! $transaction->canBeUpdated )
        {
            throw new ForbiddenHttpException(Yii::t('app', 'Not updatable in this state.'));
        }
        
        $model = new TransactionForm();
        $model->importDataFromTransaction($transaction);
        $model->begin_date = $this->periodicalReport->begin_date;
        $model->end_date = $this->periodicalReport->end_date;
        
        $model->templates = TransactionTemplate::getActiveTransactionTemplatesAsArray();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/transaction-submissions/view', 'id'=>$id]);
        }        
        
        return $this->render('/transactions/update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Transaction model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) // Deletes a transaction
    {
        $model = $this->findModel($id);
        $periodical_report_id = $model->periodical_report_id;
        
        if ( ! $model->canBeUpdated )
        {
            throw new ForbiddenHttpException(Yii::t('app', 'Not deletable in this state.'));
        }

        $model->delete();
        
        return $this->redirect(['periodical-report-submissions/view', 'id'=>$periodical_report_id]);
    }
    
    public function actionInvert($id) // Inverts the postings of a transaction
    {
        $model = $this->findModel($id);
        $periodical_report_id = $model->periodical_report_id;
        
        if ( ! $model->canBeUpdated )
        {
            throw new ForbiddenHttpException(Yii::t('app', 'Not updatable in this state.'));
        }

        if ($model->invertPostings()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Transaction inverted.'));
        }
        else {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Inversion failed.'));
        }
        
        return $this->redirect(['transaction-submissions/view', 'id'=>$id]);
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
