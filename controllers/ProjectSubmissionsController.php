<?php

namespace app\controllers;

use Yii;
use app\models\Project;
use app\models\ProjectSearch;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use app\components\CController;
use app\models\OrganizationalUnit;
use app\controllers\SubmissionsTrait;

/**
 * ProjectSubmissionsController implements the CRUD actions for Project model.
 */
class ProjectSubmissionsController extends CController
{
    use SubmissionsTrait;
    
    //public $enableCsrfValidation = false;
    
    public function init()
    {
        parent::init();
        $this->viewPath = '@app/views';
        // This is needed because we want to use the same views for both
        // submitter and manager, that use different controllers
    }
    
    public function beforeAction($action)
    {
        if (!in_array($action->id, ['list', 'details'])) {
            $this->setOrganizationalUnit($action);
            if (!$this->organizationalUnit) {
                $this->redirect(['/site/choose-organizational-unit', 'return'=>\Yii::$app->request->url]);
                return;
            }
        }
        return parent::beforeAction($action);
    }
    
    /**
     * Lists all Project models.
     * @return mixed
     */
    public function actionIndex($active=null, $pagesize=100) // Lists all the projects of the organizational unit of the logged-in user
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
    
    public function actionList($status='') // Lists all the projects approved or closed
    {
        switch ($status){
            case 'approved':
                $query = Project::find()->approved(true);
                break;
            case 'completed':
                $query = Project::find()->completed();
                break;
            default:
                $query = Project::find()->withId(-1); 
        }
        
        $searchModel = new ProjectSearch();
        $dataProvider = $searchModel->search(
            Yii::$app->request->queryParams,
            $query
        );

        $dataProvider->sort->defaultOrder = ['updated_at' => SORT_DESC];

        return $this->render('projects/list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'status' => $status,
        ]);
    }

    /**
     * Displays a single Project model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) // Displays a specific project, given its id
    {
        $project = $this->findModel($id, false);
        return $this->render('projects/view', [
            'model' => $project,
        ]);
    }

    public function actionDetails($id) // Displays a specific project, given its id
    {
        $project = $this->findModel($id, false, false);
        return $this->render('projects/details', [
            'model' => $project,
        ]);
    }

    /**
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() // Creates a new project for the organizational unit of the logged-in user
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
    public function actionUpdate($id) // Updates a project, given its id
    {
        $model = $this->findModel($id);

        $this->_lockModel($model);

        if ($model->load(Yii::$app->request->post()) && $model->save() && $model->unlock()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('projects/update', [
            'model' => $model,
        ]);
    }

    public function actionClone($id) // Clones a project
    {
        // we need to redeclare this because of the false parameter to use
        $model = $this->findModel($id, false, false)->cloneModel($this->organizationalUnit->id);
        Yii::$app->session->setFlash('success', Yii::t('app', "Project cloned."));
        return $this->redirect(['index']);//, 'id' => $model->id]);
    }

    public function actionChange($id, $status) // Changes the workflow status of a project
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
    public function actionDelete($id) // Deletes a project
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
    protected function findModel($id, $onlyUpdateable=true, $onlyOwned=true)
    {
        if ($onlyUpdateable) {
            $model = Project::find()->withId($id)->withOrganizationalUnitId($this->organizationalUnit->id)->draft()->one();
        }
        else {
            $model = Project::findOne($id);
        }
        
        if ($model) {
            if ($onlyOwned and !$model->getOrganizationalUnit()->one()->hasLoggedInUser() or $model->isDraft and !$model->getOrganizationalUnit()->one()->hasLoggedInUser()) {
                throw new ForbiddenHttpException(Yii::t('app', 'Not authorized.'));
            }

            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
