<?php

namespace app\controllers;

use Yii;
use app\models\OrganizationalUnit;
use app\models\OrganizationalUnitSearch;
use app\models\OrganizationalUnitsCeilingAmountsForm;
use app\models\ViewedOuMainActivity;
use app\models\ViewedOuMainActivitySearch;
use yii\web\NotFoundHttpException;
use app\components\CController;

/**
 * OrganizationalUnitsController implements the CRUD actions for OrganizationalUnit model.
 */
class OrganizationalUnitsController extends CController
{
    /**
     * Lists all OrganizationalUnit models.
     * @return mixed
     */
    public function actionIndex($active=null) // Lists all organizational units
    {
        $activeStatus = $active == 'false' ? false : true;
        $active = $activeStatus ? 'true': 'false';
        $searchModel = new OrganizationalUnitSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, OrganizationalUnit::find()->active($activeStatus));
        $dataProvider->sort->defaultOrder = ['rank' => SORT_ASC, 'name' => SORT_ASC];
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'active' => $active,
        ]);
    }

    public function actionText($active=null, $text='') // Lists all organizational units
    {
        $activeStatus = $active == 'false' ? false : true;
        $active = $activeStatus ? 'true': 'false';
        $searchModel = new OrganizationalUnitSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, OrganizationalUnit::find()->active($activeStatus)->withAPhoneNumber());
        $dataProvider->sort->defaultOrder = ['rank' => SORT_ASC, 'name' => SORT_ASC];
        $dataProvider->pagination = [
            'pageSize' => 1000,
        ];
        
        return $this->render('text', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'active' => $active,
            'text' => $text,
        ]);
    }
    
    public function actionSendSmsMessages()
    {
        $selectedIds = Yii::$app->request->post('selection', []);
        $smsMessage = Yii::$app->request->post('sms_message');
        $action = Yii::$app->request->post('action');
        
        // Check if user submitted the SMS action
        if ($action === 'sendsms') {
            
            // Validate that message and rows are selected
            if (empty($selectedIds)) {
                Yii::$app->session->addFlash('error', Yii::t('app', 'Please select at least one organizational unit.'));
                return $this->redirect(['text', 'text'=>$smsMessage]);
            }
            
            if (empty($smsMessage)) {
                Yii::$app->session->addFlash('error', 'Please enter an SMS message.');
                return $this->redirect(['text']);
            }
            
            // $selectedIds - array of selected organizational unit IDs
            // $smsMessage - the text message to send
            
            // Send SMS to selected units
            $count = 0;
            foreach ($selectedIds as $id) {
                $unit = OrganizationalUnit::findOne($id);
                if ($unit) {
                    if (Yii::$app->smsservice->send($unit->phone, $smsMessage)){
                        $count++;
                    }
                }
            }
            
            Yii::$app->session->addFlash('success', Yii::t('app', 'SMS sent to {count,plural,=0{no addressee} =1{one addressee} other{# addressees}}.', ['count'=>$count]));
            return $this->redirect(['text']);
        }
        
    } 

    /**
     * Displays a single OrganizationalUnit model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) // Displays an organizational unit, given its id
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new OrganizationalUnit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() // Creates an organizational unit
    {
        $model = new OrganizationalUnit();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $model->possible_actions = 0;
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OrganizationalUnit model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) // Updates an organizational unit
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
    
    public function actionCeilingAmounts() // Views and updates organizational units' plafonds
    {
        $model = new OrganizationalUnitsCeilingAmountsForm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Ceiling amounts updated for {count,plural,=0{no Organizational Unit} =1{one Organizational Unit} other{# Organizational Units}}.', ['count'=>$model->updatesCount]));
            return $this->redirect(['ceiling-amounts']);
        }
        
        $model->loadValues();

        return $this->render('ceiling-amounts', [
            'model' => $model,
        ]);
    }

    /**
     * Lists all ViewedOuMainActivity models.
     * @return mixed
     */
    public function actionMainActivities($daysBack = 365)
    {
        $searchModel = new ViewedOuMainActivitySearch();

        // Pass the received $daysBack value directly to the search model.
        // We still include queryParams for other filters (from GridView filters).
        $queryParams = Yii::$app->request->queryParams;
        $queryParams['daysBack'] = $daysBack; // Overwrite or set the daysBack parameter

        $dataProvider = $searchModel->search($queryParams);

        return $this->render('main-activities-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    


    /**
     * Finds the OrganizationalUnit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OrganizationalUnit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OrganizationalUnit::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
