<?php

namespace app\controllers;

use Yii;
use app\models\PeriodicalReport;
use app\models\PeriodicalReportSearch;
use yii\web\NotFoundHttpException;
use app\components\CController;
use app\models\PeriodicalReportsBulkCreationForm;

/**
 * PeriodicalReportsSubmissionsController implements the CRUD actions for PeriodicalReport model.
 */
class PeriodicalReportSubmissionsController extends CController
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
        $this->modelClass = PeriodicalReport::className();

        $this->setOrganizationalUnit($action);
        if (!$this->organizationalUnit) {
            $this->redirect(['/site/choose-organizational-unit', 'return'=>\Yii::$app->request->url]);
            return;
        }
        return parent::beforeAction($action);
    }

    /**
     * Lists all PeriodicalReport models.
     * @return mixed
     */
    public function actionIndex($active=null, $pagesize=100)
    {        
        $active = $active == 'false' ? false : true;
        
        $searchModel = new PeriodicalReportSearch();
        $dataProvider = $searchModel->search(
            Yii::$app->request->queryParams,
            PeriodicalReport::find()->withOrganizationalUnitId($this->organizationalUnit->id)->active($active)
            );

        $dataProvider->sort->defaultOrder = ['end_date' => SORT_DESC];
        
        $dataProvider->pagination = [
            'pageSize' => $pagesize,
        ];

        return $this->render('periodical-reports/submissions-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'organizationalUnit' => $this->organizationalUnit,
        ]);
    }

    /**
     * Displays a single PeriodicalReport model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('/periodical-reports/view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PeriodicalReport model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PeriodicalReport();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PeriodicalReport model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
    */
    /**
     * Deletes an existing PeriodicalReport model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
     /*
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    * */

    
    public function notSavingBulkActions() {
        return []; 
    }       


    public function actionChange($id, $status)
    {
        $model = $this->findModel($id, false);
        return $this->_changeWorkflowStatus($model, $status);
    }

    /**
     * Finds the PeriodicalReport model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PeriodicalReport the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = PeriodicalReport::find()->withId($id)->withOrganizationalUnitId($this->organizationalUnit->id)->one();
        
        if ($model) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
