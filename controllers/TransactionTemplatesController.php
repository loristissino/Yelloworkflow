<?php

namespace app\controllers;

use Yii;
use app\models\TransactionTemplate;
use app\models\TransactionTemplateSearch;
use yii\web\NotFoundHttpException;
use app\components\CController;

/**
 * TransactionTemplatesController implements the CRUD actions for TransactionTemplate model.
 */
class TransactionTemplatesController extends CController
{
    /**
     * Lists all TransactionTemplate models.
     * @return mixed
     */
    public function actionIndex($active=null, $pagesize=100) // Lists all transaction templates
    {
        $active = $active == 'false' ? false : true;
        
        $searchModel = new TransactionTemplateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, TransactionTemplate::find()->active($active));
        
        $dataProvider->sort->defaultOrder = ['rank' => SORT_ASC];
        
        $dataProvider->pagination = [
            'pageSize' => $pagesize,
        ];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TransactionTemplate model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) // Displays a transaction template
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TransactionTemplate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() // Creates a transaction template
    {
        $model = new TransactionTemplate();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TransactionTemplate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) // Updates a transaction template
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TransactionTemplate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) // Deletes a transaction template
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionClone($id) // Clones a transaction template
    {
        $model = $this->findModel($id, false)->cloneModel();
        Yii::$app->session->setFlash('success', Yii::t('app', "Transaction template cloned."));
        return $this->redirect(['index']);//, 'id' => $model->id]);
    }    

    /**
     * Finds the TransactionTemplate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TransactionTemplate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TransactionTemplate::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
