<?php

namespace app\controllers;

use Yii;
use app\models\PetitionSignature;
use app\models\PetitionSignatureSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\CController;

/**
 * PetitionSignaturesController implements the CRUD actions for PetitionSignature model.
 */
class PetitionSignaturesController extends CController
{

    /**
     * Lists all PetitionSignature models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PetitionSignatureSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionList($slug, $withMessages='true')
    {
        $petition = \app\models\Petition::find()->withSlug($slug)->one();
        if (!$petition) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));        
        }
        
        $signatures = $petition->getPetitionSignatures()->confirmed()->orderBy(['created_at'=>SORT_ASC]);
        
        return $this->render('list', [
            'petition'=>$petition,
            'signatures'=>$signatures,
            'with_messages'=>($withMessages=='false' ? false: true),
        ]);
    }

    /**
     * Displays a single PetitionSignature model.
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
     * Creates a new PetitionSignature model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
     /*
    public function actionCreate()
    {
        $model = new PetitionSignature();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    */

    /**
     * Updates an existing PetitionSignature model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->setSlug() && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PetitionSignature model.
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
     * Finds the PetitionSignature model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PetitionSignature the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PetitionSignature::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
