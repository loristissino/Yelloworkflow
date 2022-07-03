<?php

namespace app\controllers;

use Yii;
use app\models\Authorization;
use app\models\AuthorizationSearch;
use yii\web\NotFoundHttpException;
use app\components\CController;

/**
 * AuthorizationsController implements the CRUD actions for Authorization model.
 */
class AuthorizationsController extends CController
{
    
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs']['actions']['revoke'] = ['POST'];
        return $behaviors;
    }    
    
    /**
     * Lists all Authorization models.
     * @return mixed
     */
    public function actionIndex($active=null, $pagesize=100) // Lists all authorizations
    {
        $active = $active == 'false' ? false : true;
        
        $searchModel = new AuthorizationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Authorization::find()->active($active));

        // $dataProvider->sort->defaultOrder = ['controller_id' => SORT_ASC, 'action_id' => SORT_ASC, 'method' => SORT_ASC];
        $dataProvider->sort->defaultOrder = ['id' => SORT_ASC];
        
        $dataProvider->pagination = [
            'pageSize' => $pagesize,
        ];
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Authorization model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) // Displays a specific authorization, given its id
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Authorization model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() // Creates a new authorization
    {
        $model = new Authorization();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Authorization model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) // Updates a specific authorization, given its id
    {
        $model = $this->findModel($id);
        
        $this->_lockModel($model);

        if ($model->load(Yii::$app->request->post()) && $model->save() && $model->unlock()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionRevoke($id) // Revokes a specific authorization, given its id
    {
        $model = $this->findModel($id);
        $model->revoke()->save();
        Yii::$app->session->setFlash('success', Yii::t('app', 'Authorization revoked.'));
        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Deletes an existing Authorization model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) // Deletes a specific authorization, given its id [works only if the authorization has never been used]
    {
        try {
            $authorization = $this->findModel($id);
            if ($authorization->canBeDeleted()) {
                $authorization->delete();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Authorization deleted.'));
            }
            else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'This authorization has been used and cannot be deleted.'));
                return $this->redirect(['authorizations/view', 'id'=>$id]);
            }
        }
        catch (Exception $e) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'The authorization could not be deleted.'));
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Authorization model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Authorization the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Authorization::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
