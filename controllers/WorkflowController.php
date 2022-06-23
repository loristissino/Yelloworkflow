<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use app\components\CController;
use app\models\WorkflowForm;

/**
 * WorkflowController allows an easy update, for administrative reasons, of models' workflow status.
 */
class WorkflowController extends CController
{
    /**
     * Updates the workflow status of a model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($type, $id, $return) // Updates the status of a model
    {
        //return $this->redirect(Yii::$app->request->referrer);
        $object = call_user_func("$type::findOne", $id);
        if (!$object) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
        
        $model = new WorkflowForm();
        $model->model = $type;
        $model->object = $object;
        $model->id = $object->id;
        $model->status = $object->wf_status;
        $model->oldStatus = $object->wf_status;
        
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Workflow Status successfully changed for item {id}.', ['id'=>$model->id]));
                return $this->redirect($return);
            }
            else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Workflow Status not changed for item {id}.', ['id'=>$model->id]));
            }
        }
        
        return $this->render('update', [
            'model' => $model,
            'return' => $return,
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
