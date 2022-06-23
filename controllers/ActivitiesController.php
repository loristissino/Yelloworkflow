<?php

namespace app\controllers;

use Yii;
use app\models\Activity;
use app\models\ActivitytSearch;
use yii\web\NotFoundHttpException;
use app\components\CController;


/**
 * ActivitiesController implements the CRUD actions for Activity model.
 */
class ActivitiesController extends CController
{

    /**
     * Lists all Activity models.
     * @return mixed
     */
    public function actionIndex($pagesize=100) // Lists logged activities
    {
        $searchModel = new ActivitytSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->sort->defaultOrder = ['happened_at' => SORT_DESC, 'id' => SORT_DESC];
        
        $dataProvider->pagination = [
            'pageSize' => $pagesize,
        ];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Activity model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) // Displays details about a specific logged activity, given its id
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Activity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Activity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Activity::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
