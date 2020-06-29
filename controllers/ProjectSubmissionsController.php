<?php

namespace app\controllers;

use Yii;
use app\models\Project;
use app\models\ProjectSearch;
use yii\web\NotFoundHttpException;
use app\components\CController;
use app\models\OrganizationalUnit;
use app\controllers\SubmissionsTrait;

/**
 * ProjectSubmissionsController implements the CRUD actions for Project model.
 */
class ProjectSubmissionsController extends CController
{
    use SubmissionsTrait;
    
    public function init()
    {
        $this->viewPath = '@app/views';
        // This is needed because we want to use the same views for both
        // submitter and manager, that use different controllers
    }
    
    public function beforeAction($action)
    {
        $this->setOrganizationalUnit($action);
        if (!$this->organizationalUnit) {
            $this->redirect(['/site/choose-organizational-unit', 'return'=>\Yii::$app->request->url]);
            return;
        }
        return parent::beforeAction($action);
    }
    
    /**
     * Lists all Project models.
     * @return mixed
     */
    public function actionIndex($active=null, $pagesize=100)
    {
        $active = $active == 'false' ? false : true;
        
        $searchModel = new ProjectSearch();
        $dataProvider = $searchModel->search(
            Yii::$app->request->queryParams,
            Project::find()->withOrganizationalUnitId($this->organizationalUnit->id)->active($active)
        );

        $dataProvider->sort->defaultOrder = ['updated_at' => SORT_DESC];

        return $this->render('projects/submissions-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'organizationalUnit' => $this->organizationalUnit,
        ]);
    }

    /**
     * Displays a single Project model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('projects/view', [
            'model' => $this->findModel($id, false),
        ]);
    }

    /**
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Project();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('projects/create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Project model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('projects/update', [
            'model' => $model,
        ]);
    }

    public function actionClone($id)
    {
        // we need to redeclare this because of the false parametero to use
        $model = $this->findModel($id, false)->cloneModel();
        Yii::$app->session->setFlash('success', Yii::t('app', "Project cloned."));
        return $this->redirect(['index']);//, 'id' => $model->id]);
    }

    public function actionChange($id, $status)
    {
        $model = $this->findModel($id, false);
        return $this->_changeWorkflowStatus($model, $status);
    }
    
    /**
     * Deletes an existing Project model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->sendToStatus('deleted');
        $model->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $onlyUpdateable=true)
    {
        if ($onlyUpdateable) {
            $model = Project::find()->withId($id)->withOrganizationalUnitId($this->organizationalUnit->id)->draft()->one();
        }
        else {
            $model = Project::findOne($id);
        }
        
        if ($model) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
