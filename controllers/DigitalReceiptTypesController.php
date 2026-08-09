<?php

namespace app\controllers;

use Yii;
use app\models\DigitalReceiptType;
use app\models\DigitalReceiptTypeSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\CController;

/**
 * DigitalReceiptTypesController implements the CRUD actions for DigitalReceiptType model.
 */
class DigitalReceiptTypesController extends CController
{

    /**
     * Lists all DigitalReceiptType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DigitalReceiptTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DigitalReceiptType model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DigitalReceiptType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DigitalReceiptType();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing DigitalReceiptType model.
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

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing DigitalReceiptType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionApiConfig($id)
    {
        $model = $this->findModel($id);
        $validatorClass = $model->validator;
        
        $apiForm = new \app\components\api\digitalreceipts\ApiConfigForm();
        
        $request = Yii::$app->request;

        if ($request->isPost) {
            $apiForm->load($request->post()); 
        
            // Call your method
            try {
                $result = $validatorClass::setConfiguration($model, $apiForm->taxCode, $apiForm->password, $apiForm->pin);
                Yii::$app->session->setFlash('success', 'API configuration updated. Check below if the result was successful.');
                Yii::$app->session->setFlash('api_result', $result);
            } catch (Exception $e) {
                Yii::$app->session->setFlash('error', 'Error: ' . $e->getMessage());
            }
        
            return $this->redirect(['api-config', 'id' => $id]);
        }

        return $this->render('apiconfig', [
            'model' => $model,
            'configuration' => $validatorClass::getConfiguration($model),
            'apiForm' => $apiForm,
        ]);
    }

    /**
     * Finds the DigitalReceiptType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DigitalReceiptType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DigitalReceiptType::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
