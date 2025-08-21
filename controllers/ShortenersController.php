<?php

namespace app\controllers;

use Yii;
use yii\filters\VerbFilter;
use app\components\CController;
use app\models\Shortener;
use yii\web\NotFoundHttpException;

/**
 * ShortenersController implements the CRUD actions for URL Shorteners model.
 */
 
class ShortenersController extends CController
{

    public function actionIndex($limit=20, $perpage=10)
    {
        $dataProvider = Shortener::findLatest($limit, $perpage);
        return $this->render('index', [
            'dataProvider'=>$dataProvider,
            'limit'=>$limit,
        ]);
        
    }

    /**
     * Displays a single Shortener model.
     * @param integer $key
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($keyword)
    {
        return $this->render('view', [
            'model' => $this->findModel($keyword),
        ]);
    }

    /**
     * Creates a new Shortener model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Shortener();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                \app\components\LogHelper::log(sprintf('created [%s]', $model->keyword), $model, ['key'=>$model->keyword]);
                return $this->redirect(['view', 'keyword' => $model->keyword]);
            }
            else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'The URL could not be shortened for unknown reasons.'));
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Shortener model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $key
     * @return Shortener the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($keyword)
    {
        if (($model = Shortener::findOne($keyword)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
