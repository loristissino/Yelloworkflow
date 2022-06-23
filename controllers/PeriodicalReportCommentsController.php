<?php

namespace app\controllers;

use Yii;
use app\models\PeriodicalReportComment;
use app\models\PeriodicalReportCommentSearch;
use yii\web\NotFoundHttpException;
use app\components\CController;

/**
 * PeriodicalReportCommentsController implements the CRUD actions for PeriodicalReportComment model.
 */
class PeriodicalReportCommentsController extends CController
{
    public $periodicalReport = null;
    
    /**
     * Creates a new PeriodicalReportComment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($periodicalReport, $controller, $reply_to=null) // Creates a comment linked to a periodicalReport
    {
        $this->periodicalReport = $this->findPeriodicalReport($periodicalReport);
        $model = new PeriodicalReportComment();

        if ($model->load(Yii::$app->request->post()) && $model->setPeriodicalReport($this->periodicalReport)->setUserId(Yii::$app->user->identity->id) && $model->save()) {
            if ($model->immediately_question_periodical_report) {
                return $this->redirect(['periodical-reports-management/change', 'id' => $this->periodicalReport->id, 'status'=>'questioned']);
            }
            return $this->redirect([$controller.'/view', 'id' => $this->periodicalReport->id]);
        }

        if ($reply_to) {
            $reply_to = $this->findModel($reply_to, false);
        }

        return $this->render('/periodical-report-comments/create', [
            'model' => $model,
            'periodicalReport' => $this->periodicalReport,
            'controller' => $controller,
            'reply_to' => $reply_to,
        ]);
    }

    /**
     * Updates an existing PeriodicalReportComment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $controller) // Updates a comment linked to a periodical report, given its id
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([$controller.'/view', 'id' => $this->periodicalReport->id]);
        }

        return $this->render('/periodical-report-comments/update', [
            'model' => $model,
            'periodicalReport' => $this->periodicalReport,
            'controller' => $controller,
        ]);
    }

    /**
     * Deletes an existing PeriodicalReportComment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $controller) // Deletes a comment linked to a periodical report, given its id
    {
        $this->findModel($id)->delete();

        return $this->redirect([$controller . '/view', 'id' => $this->periodicalReport->id]);
    }

    /**
     * Finds the PeriodicalReportComment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PeriodicalReportComment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $checkOwnership = true)
    {
        $model = $checkOwnership ? 
            PeriodicalReportComment::find()->ofUser(Yii::$app->user->identity->id)->withId($id)->one()
            :
            PeriodicalReportComment::find()->withId($id)->one()
            ;
        if ($model) {
            $this->findPeriodicalReport($model->periodical_report_id);
        }
        if (!$model) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
        return $model;
    }
    
    protected function findPeriodicalReport($id)
    {
        if (($this->periodicalReport = \app\models\PeriodicalReport::find()->withId($id)->draft(false)->one()) !== null) {
            return $this->periodicalReport;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
