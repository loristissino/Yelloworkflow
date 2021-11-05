<?php

namespace app\controllers;

use Yii;
use app\models\TransactionTemplate;
use app\models\TransactionTemplatePosting;
use app\models\TransactionTemplatePostingSearch;
use yii\web\NotFoundHttpException;
use app\components\CController;

/**
 * TransactionTemplatePostingsController implements the CRUD actions for TransactionTemplatePosting model.
 */
class TransactionTemplatePostingsController extends CController
{
    /**
     * Lists all TransactionTemplatePosting models.
     * @return mixed
     */
    public function actionIndex() // Lists the transaction templates' postings
    {
        $searchModel = new TransactionTemplatePostingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TransactionTemplatePosting model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) // Displays a transaction template's posting
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TransactionTemplatePosting model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($template) // Creates a transaction template's posting
    {
        $template = $this->findTransactionTemplate($template);

        $model = new TransactionTemplatePosting();
        
        $model->setTransactionTemplate($template);
        
        if ($model->load(Yii::$app->request->post()) && $model->setTransactionTemplate($template) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'template' => $template,
        ]);
    }

    /**
     * Updates an existing TransactionTemplatePosting model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) // Updates a transanction template's posting
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TransactionTemplatePosting model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) // Deletes a transaction template's posting
    {
        $model = $this->findModel($id);
        
        $template_id = $model->transaction_template_id;
        
        $model->delete();

        return $this->redirect(['transaction-templates/view', 'id'=>$template_id]);
    }

    /**
     * Finds the TransactionTemplatePosting model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TransactionTemplatePosting the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TransactionTemplatePosting::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    protected function findTransactionTemplate($id)
    {
        if (($model = TransactionTemplate::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
