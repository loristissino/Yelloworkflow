<?php

namespace app\controllers;

use Yii;
use app\models\ProjectComment;
use app\models\ProjectCommentSearch;
use yii\web\NotFoundHttpException;
use app\components\CController;

/**
 * ProjectCommentsController implements the CRUD actions for ProjectComment model.
 */
class ProjectCommentsController extends CController
{
    public $project = null;
    /**
     * Lists all ProjectComment models.
     * @return mixed
     */
     /*
    public function actionIndex()
    {
        $searchModel = new ProjectCommentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    */
    /**
     * Displays a single ProjectComment model.
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
    */

    /**
     * Creates a new ProjectComment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($project, $controller, $reply_to=null)
    {
        $this->project = $this->findProject($project);
        $model = new ProjectComment();

        if ($model->load(Yii::$app->request->post()) && $model->setProject($this->project)->setUserId(Yii::$app->user->identity->id) && $model->save()) {
            return $this->redirect([$controller.'/view', 'id' => $this->project->id]);
        }

        if ($reply_to) {
            $reply_to = $this->findModel($reply_to, false);
        }

        return $this->render('/project-comments/create', [
            'model' => $model,
            'project' => $this->project,
            'controller' => $controller,
            'reply_to' => $reply_to,
        ]);
    }



    /**
     * Updates an existing ProjectComment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $controller)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([$controller.'/view', 'id' => $this->project->id]);
        }

        return $this->render('/project-comments/update', [
            'model' => $model,
            'project' => $this->project,
            'controller' => $controller,
        ]);
    }

    /**
     * Deletes an existing ProjectComment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $controller)
    {
        $this->findModel($id)->delete();

        return $this->redirect([$controller . '/view', 'id' => $this->project->id]);
    }

    /**
     * Finds the ProjectComment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProjectComment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $checkOwnership = true)
    {
        $model = $checkOwnership ? 
            ProjectComment::find()->ofUser(Yii::$app->user->identity->id)->withId($id)->one()
            :
            ProjectComment::find()->withId($id)->one()
            ;
        if ($model) {
            $this->findProject($model->project_id);
        }
        if (!$model) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
        return $model;
    }
    
    protected function findProject($id)
    {
        if (($this->project = \app\models\Project::find()->withId($id)->draft(false)->one()) !== null) {
            return $this->project;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
