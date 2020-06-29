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
     * Lists all PlannedExpense models.
     * @return mixed
     */
    /*
    public function actionIndex()
    {
        $searchModel = new PlannedExpenseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    */
    /**
     * Displays a single PlannedExpense model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    **/

    /**
     * Creates a new PlannedExpense model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($project)
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
    public function actionUpdate($id)
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
    public function actionDelete($id)
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
