<?php

namespace app\controllers;

use Yii;
use app\models\Attachment;
use app\models\AttachmentSearch;
use yii\data\ActiveDataProvider;
use app\components\CController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\SqlDataProvider;


/**
 * DocumentsController implements the CRUD actions for Attachment model.
 */
class DocumentsController extends CController
{

    use SubmissionsTrait;

    public function beforeAction($action)
    {
        if (!in_array($action->id, [])) {
            $this->setOrganizationalUnit($action);
            if (!$this->organizationalUnit) {
                return $this->redirect(['/site/choose-organizational-unit', 'return'=>\Yii::$app->request->url]);
            }
        }
        return parent::beforeAction($action);
    }

    /**
     * Lists all Attachment models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        $sql = "SELECT `attachments`.`id` AS `id`, `attachments`.`name` AS `name`, `attachments`.`hash` AS `hash`, `attachments`.`size` AS `size`, `attachments`.`type` as `type`, `attachments`.`itemId` as `itemId`, `attachments`.`model` as `model`, `transactions`.`description` as `description`, `transactions`.`created_at` AS `created_at` FROM `attachments` JOIN `transactions` ON `itemId`=`transactions`.`id` JOIN `periodical_reports` ON `transactions`.`periodical_report_id`=`periodical_reports`.`id` WHERE `model`='Transaction' AND `periodical_reports`.`organizational_unit_id` = :id 
        
        UNION

        SELECT `attachments`.`id` AS `id`, `attachments`.`name` AS `name`, `attachments`.`hash` AS `hash`, `attachments`.`size` AS `size`, `attachments`.`type` as `type`, `attachments`.`itemId` as `itemId`, `attachments`.`model` as `model`, `periodical_reports`.`name` as `description`, `periodical_reports`.`created_at` AS `created_at`  FROM `attachments` JOIN `periodical_reports` ON `itemId`=`periodical_reports`.`id` WHERE `model`='PeriodicalReport' AND `periodical_reports`.`organizational_unit_id` = :id
        
        ORDER BY `created_at` DESC";
        
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'params' => [':id' => $this->organizationalUnit->id],
            'pagination' => false,
            ]
        );

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'organizationalUnit' => $this->organizationalUnit,
        ]);

        /*
        $dataProvider = new ActiveDataProvider([
            'query' => Attachment::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
        */
    }

    /**
     * Displays a single Attachment model.
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
     * Creates a new Attachment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Attachment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Attachment model.
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
     * Deletes an existing Attachment model.
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

    /**
     * Finds the Attachment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Attachment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Attachment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
