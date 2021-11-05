<?php

namespace app\controllers;

use Yii;
use app\models\PlannedExpense;
use app\models\PlannedExpenseSearch;
use yii\web\NotFoundHttpException;
use app\components\CController;

/**
 * PlannedExpensesController implements the CRUD actions for PlannedExpense model.
 */
class PlannedExpensesController extends CController
{

    public $project = null;

    /**
     * Creates a new PlannedExpense model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($project) // Creates a new planned expense linked to a project
    {
        $this->findProject($project);
        $model = new PlannedExpense();

        if ($model->load(Yii::$app->request->post()) && $model->setProject($this->project) && $model->save(false)) {
            return $this->redirect(['project-submissions/view', 'id' => $this->project->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'project' => $this->project,
        ]);
    }

    /**
     * Updates an existing PlannedExpense model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) // Updates a planned expense, given its id
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post())  && $model->setProject($this->project) && $model->save()) {
            return $this->redirect(['project-submissions/view', 'id' => $this->project->id]);
        }

        return $this->render('/planned-expenses/update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PlannedExpense model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) // Deletes a planned expense, given its id
    {
        $this->findModel($id)->delete();

        return $this->redirect(['project-submissions/view', 'id' => $this->project->id]);
    }

    /**
     * Finds the PlannedExpense model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PlannedExpense the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = PlannedExpense::findOne($id);
        if ($model) {
            $this->findProject($model->project_id);
            return $model;
        }
        else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    protected function findProject($id)
    {
        $this->project = \app\models\Project::find()->withId($id)->draft()->withOrganizationalUnitId(Yii::$app->session->get('organizational_unit_id'))->one();
        if (!$this->project) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
    
}
