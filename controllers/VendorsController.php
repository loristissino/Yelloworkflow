<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use app\models\Transaction;
use app\components\CController;

/**
 * VendorsController allows editing of vendors data (from transactions).
 */
class VendorsController extends CController
{
    public function actionIndex() // List all vendors
    {
        $dataProvider = Transaction::getKnownVendors(true);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id) // Displays a specific transaction (only fields connected to vendor, given its id
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing transaction (only vendor data).
     */
    public function actionUpdate($id) // Updates an expense type
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
     * Finds the Transactio model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Transaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Transaction::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
