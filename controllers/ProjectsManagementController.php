<?php

namespace app\controllers;

use Yii;
use app\models\Project;
use app\models\ProjectSearch;
use yii\web\NotFoundHttpException;
use app\components\CController;

/**
 * ProjectsManagementController implements the CRUD actions for Project model.
 */
class ProjectsManagementController extends CController
{
    public function init()
    {
        $this->viewPath = '@app/views';
        // This is needed because we want to use the same views for both
        // submitter and manager, that use different controllers
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
            Project::find()->active($active)->draft(false)
        );

        $dataProvider->sort->defaultOrder = ['updated_at' => SORT_DESC];
        
        $dataProvider->pagination = [
            'pageSize' => $pagesize,
        ];

        return $this->render('projects/management-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
            'model' => $this->findModel($id),
        ]);
    }

    public function actionChange($id, $status)
    {
        $model = $this->findModel($id);
        return $this->_changeWorkflowStatus($model, $status);
    }
    
    /**
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Project::find()->withId($id)->draft(false)->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
