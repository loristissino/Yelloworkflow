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
        parent::init();
        $this->viewPath = '@app/views';
        // This is needed because we want to use the same views for both
        // submitter and manager, that use different controllers
    }
        
    /**
     * Lists all Project models.
     * @return mixed
     */
    public function actionIndex($active=null, $pagesize=100) // List all submitted projects
    {
        $active = $active == 'false' ? false : true;
        
        $searchModel = new ProjectSearch();
        $dataProvider = $searchModel->search(
            Yii::$app->request->queryParams,
            Project::find()->active($active)->workedOn()
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
    public function actionView($id, $template='view') // Displays a submitted project, given its id
    {
        if ($template=='copyandpaste') {
            $this->layout = 'print';
        }
        return $this->render('projects/' . $template, [
            'model' => $this->findModel($id),
        ]);
    }
    
    public function actionChange($id, $status) // Changes the workflow status of a project
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
        if (($model = Project::find()->withId($id)->workedOn()->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
