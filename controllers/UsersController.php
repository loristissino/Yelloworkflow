<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\web\NotFoundHttpException;
use app\components\CController;
use app\models\UsersRenewalsForm;

/**
 * UsersController implements the CRUD actions for User model.
 */
class UsersController extends CController
{

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex($active=null) // Lists all users
    {
        $activeStatus = $active == 'false' ? false : true;
        $active = $activeStatus ? 'true': 'false';
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, User::find()->active($activeStatus));

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'active' => $active,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) // Displays a specific user
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() // Creates a new user
    {
        $model = new User();
        
        $model->auth_key = rand(100000000, 999999999); // it must be set by the user anyway...
        $model->status = 1;

        if ($model->load(Yii::$app->request->post()) && $model->setRandomValuesForUnusedFields() && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) // Updates a specific user
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
    
    public function actionRenewals()
    {
        $model = new UsersRenewalsForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
            $count = sizeof($model->updated);
            $flash = Yii::t('app', 'Last renewal updated for {count,plural,=0{no users} =1{one user} other{# users}}.', ['count'=>$count]);
            if ($count>0) {
                $names = [];
                foreach($model->updated as $user) {
                    $names[]=$user->fullName;
                }
                $flash .= '<br />' . Yii::t('app', 'Updated users: {list}.', ['list'=>join(', ', $names)]);
            }
            Yii::$app->session->setFlash('success', $flash);
            return $this->redirect(['index']);
        }

        if (!$model->year) {
            $model->year = date('Y');
        }
        
        return $this->render('renewals', [
            'model' => $model,
        ]);
        
    }

    /*
    public function aactionPassword($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->encryptPassword() && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $model->auth_key = '';

        return $this->render('password', [
            'model' => $model,
        ]);
    }
    */

    public function actionFixPermissions($id) // Fixes the authorizations for a user
    {
        $model = $this->findModel($id);
        
        $model->fixPermissions();

        Yii::$app->session->setFlash('success', Yii::t('app', 'Permissions fixed.'));

        return $this->redirect(['view', 'id' => $model->id]);
        
    }

    public function beforeAction($action)
    {
        $this->modelClass = User::className();
        if (!parent::beforeAction($action)) {
            return false;
        }
        return true;
    }

    public function notSavingBulkActions() {
        return ['fixPermissions'];
    }   

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
