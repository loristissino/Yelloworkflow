<?php

namespace app\controllers;

use Yii;
use app\models\NotificationTemplate;
use app\models\NotificationTemplateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\CController;

/**
 * NotificationTemplatesController implements the CRUD actions for NotificationTemplate model.
 */
class NotificationTemplatesController extends CController
{

    /**
     * Lists all NotificationTemplate models.
     * @return mixed
     */
    public function actionIndex() // Lists all notification templates
    {
        $searchModel = new NotificationTemplateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single NotificationTemplate model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) // Displays a specific notification template, given its id
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new NotificationTemplate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() // Creates a notification template
    {
        $model = new NotificationTemplate();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing NotificationTemplate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) // Updates a notification template
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
     * Deletes an existing NotificationTemplate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) // Deletes a notification template
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionEmailTest()
    {
        $email = Yii::$app->params['adminEmail'];
        if (Yii::$app->mailer->compose()
            ->setTo($email, 'YWF Admin (TEST)')
            ->setSubject('This is a test - ' . time())
            ->setHtmlBody('<p>A test from YWF.</p>')
            ->send()
        ) {
            die("Email sent to $email.");
        }
        else {
            die("The email could not be sent.");
        }
    }

    /**
     * Finds the NotificationTemplate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return NotificationTemplate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NotificationTemplate::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
