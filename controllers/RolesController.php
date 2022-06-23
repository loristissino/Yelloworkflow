<?php

namespace app\controllers;

use Yii;
use app\models\Role;
use app\models\RoleSearch;
use yii\web\NotFoundHttpException;
use app\components\CController;

/**
 * RolesController implements the CRUD actions for Role model.
 */
class RolesController extends CController
{
    /**
     * Lists all Role models.
     * @return mixed
     */
    public function actionIndex($active=null, $pagesize=100) // Lists all roles
    {
        $active = $active == 'false' ? false : true;
        
        $searchModel = new RoleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Role::find()->active($active));

        $dataProvider->sort->defaultOrder = ['rank' => SORT_ASC];

        $dataProvider->pagination = [
            'pageSize' => $pagesize,
        ];
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionRenewals()
    {
        $roles = Role::find()->withRequiredMembership()->all();
        return $this->render('renewals', [
            'roles' => $roles,
        ]);
    }

    /**
     * Displays a single Role model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) // Displays a role
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Role model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() // Creates a new role
    {
        $model = new Role();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Role model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) // Updates a role
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
     * Finds the Role model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Role the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Role::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
