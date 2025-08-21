<?php

namespace app\controllers;

use Yii;
use app\models\Questionnaire;
use app\models\QuestionnaireForm;
use app\models\QuestionnaireResponse;
use app\models\QuestionnaireResponseSearch;
use app\components\CController;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/**
 * QuestionnaireResponsesController implements the CRUD actions for QuestionnaireResponse model.
 */
class QuestionnaireResponsesController extends CController
{

    /**
     * Lists all QuestionnaireResponse models.
     * @return mixed
     */
    /*
    public function actionIndex()
    {
        $searchModel = new QuestionnaireResponseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    */
    public function actionIndex()
    {
        return $this->render('index', [
            'questionnairesDataProvider' => new ActiveDataProvider([
                'query' => Questionnaire::find()->active(),
            ]),
            'responsesDataProvider' => new ActiveDataProvider([
                'query' => QuestionnaireResponse::find()->filledBy(Yii::$app->user->id),
            ]),
        ]);
    }

    /**
     * Displays a single QuestionnaireResponse model.
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
     * Creates a new QuestionnaireResponse model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /*
    public function actionCreate()
    {
        $model = new QuestionnaireResponse();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    */

    /**
     * Updates an existing QuestionnaireResponse model.
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
     * Deletes an existing QuestionnaireResponse model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        if ($model->isDraft){
            $model->delete();
            Yii::$app->session->setFlash('success', Yii::t('app', 'The response was successfully deleted!'));

        }

        return $this->redirect(['index']);
    }

    /**
     * Displays a questionnaire form for users to fill.
     * @param int $id The ID of the questionnaire.
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the questionnaire cannot be found.
     */
    public function actionFill($id, $response=null, $preview=false, $clone=0)
    {
        // 1. Find the questionnaire from the database
        //$questionnaire = Questionnaire::find()->where(['id' => $id])->one($id);
        $questionnaire = Questionnaire::find()->where(['id' => $id])->one();
        //$questionnaire = Questionnaire::findOne($id);
        if (!$questionnaire) {
            throw new NotFoundHttpException('The requested questionnaire does not exist.');
        }

        // 2. Create the dynamic form model based on the questionnaire's definition
        $formModel = new QuestionnaireForm(JSON_decode($questionnaire->definition, true), $questionnaire->id);

        // 3. Handle form submission
        if ($formModel->load(Yii::$app->request->post()) && $formModel->validate()) {
            // Data is valid! Save the answers.
            
            if ($response && $clone==0) {
                $questionnaireResponse = QuestionnaireResponse::findOne($response);
            }
            else {
                $questionnaireResponse = new QuestionnaireResponse();
                $questionnaireResponse->questionnaire_id = $questionnaire->id;
                $questionnaireResponse->user_id = Yii::$app->user->isGuest ? null : Yii::$app->user->id; // Assign current user ID if logged in
            }
            
            $questionnaireResponse->label = trim($formModel->label);
            
            $questionnaireResponse->content = JSON_encode($formModel->getResponseData()); // Get the collected answers as an array
            
            if ($questionnaireResponse->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Your response has been saved successfully. You can now review your answers or submit them.'));
                return $this->redirect(['questionnaire-responses/view-response', 'id' => $questionnaireResponse->id]); // Redirect to a page showing answers or a thank you
            } else {
                Yii::$app->session->setFlash('error', 'Failed to save your answers: ' . json_encode($questionnaireResponse->getErrors()));
            }
        }

        // 4. Render the form
        
        if ($response) {
            $questionnaireResponse = QuestionnaireResponse::findOne($response);
            if ($questionnaireResponse) {
                $formModel->setResponseData(JSON_decode($questionnaireResponse->content, true));
                $formModel->label = $questionnaireResponse->label;
                if ($clone==1) {
                    $questionnaireResponse->id = null;
                }
            }
        }
        
        return $this->render('fill', [
            'questionnaire' => $questionnaire,
            'formModel' => $formModel,
            'preview' => $preview,
        ]);
    }

    /**
     * Displays a preview of a questionnaire form.
     * @param int $id The ID of the questionnaire.
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the questionnaire cannot be found.
     */
    public function actionPreview($id)
    {
        return $this->actionFill($id, null, true);
    }
    
    public function actionChange($id, $status) // Changes the workflow status of a project
    {
        $model = $this->findModel($id, false);
        return $this->_changeWorkflowStatus($model, $status, ['questionnaire-responses/index']);
    }

    // You might want an action to view submitted answers
    public function actionViewResponse($id)
    {
        $questionnaireResponse = $this->findModel($id);

        if (!$questionnaireResponse) {
            throw new NotFoundHttpException('The requested response does not exist.');
        }

        return $this->render('questionnaire_response', [
            'questionnaireResponse' => $questionnaireResponse,
            'questionnaire' => $questionnaireResponse->questionnaire,
        ]);
    }

    /**
     * Finds the QuestionnaireResponse model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return QuestionnaireResponse the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = QuestionnaireResponse::findOne($id);
        
        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        if ($model->user_id !== Yii::$app->user->identity->id) {
            throw new ForbiddenHttpException(Yii::t('app', 'Not authorized.'));
        }
        
        return $model;

    }
}
