<?php

namespace app\controllers;

use Yii;
use app\models\DigitalReceipt;
use app\models\DigitalReceiptSearch;
use app\models\DigitalReceiptLine;
use app\models\DigitalReceiptType;
use app\models\DigitalReceiptProcessReturnForm;
use app\models\Product;
use app\components\LogHelper;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use app\components\CController;
use yii\db\Expression;

/**
 * DigitalReceiptsController implements the CRUD actions for DigitalReceipt model.
 */
class DigitalReceiptsController extends CController
{
    
    use SubmissionsTrait;

    public function beforeAction($action)
    {
        $this->setOrganizationalUnit($action);
        if (!$this->organizationalUnit) {
            $this->redirect(['/site/choose-organizational-unit', 'return'=>\Yii::$app->request->url]);
            return;
        }
        // we need a default for new users' sessions
        if (!Yii::$app->session->get('digitalReceiptType', false)===false){
            $drt = DigitalReceiptType::find()->active()->one();
            Yii::$app->session->set('digitalReceiptType', $drt);
        };

        $this->modelClass = DigitalReceipt::className();

        return parent::beforeAction($action);
    }

    /**
     * Lists all DigitalReceipt models.
     * @return mixed
     */
    public function actionIndex($active=null, $pagesize=30)
    {
        $active = $active == 'false' ? false : true;
        
        $searchModel = new DigitalReceiptSearch();
        //die($this->organizationalUnit->id);
        $dataProvider = $searchModel->search(
            Yii::$app->request->queryParams,
            DigitalReceipt::find()
                ->withOrganizationalUnitId($this->organizationalUnit->id)
                ->joinWith('digitalReceiptType')
        );

        $dataProvider->sort->defaultOrder = ['created_at' => SORT_DESC];
        
        $dataProvider->pagination = [
            'pageSize' => $pagesize,
        ];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'organizationalUnit' => $this->organizationalUnit,
            'digitalReceiptTypes' => DigitalReceiptType::find()->all()
        ]);
    }

    public function actionManage($pagesize=30)
    {
        if (!Yii::$app->user->hasAuthorizationFor('periodical-reports-management')){
            throw new ForbiddenHttpException(Yii::t('app', 'Not authorized.'));
        }
        $searchModel = new DigitalReceiptSearch();
        $dataProvider = $searchModel->search(
            Yii::$app->request->queryParams,
            DigitalReceipt::find()
                ->joinWith('digitalReceiptType')
        );

        $dataProvider->sort->defaultOrder = ['created_at' => SORT_DESC];
        
        $dataProvider->pagination = [
            'pageSize' => $pagesize,
        ];

        return $this->render('manage', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DigitalReceipt model.
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
     * Creates a new DigitalReceipt model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($type)
    {
        $drt = DigitalReceiptType::findOne($type);
        if (!$drt) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
        
        Yii::$app->session->set('digitalReceiptType', $drt);
        
        $model = new DigitalReceipt();

        if ($model->load(Yii::$app->request->post()) && 
                $model->setDefaults($drt, $this->organizationalUnit) && 
                $model->saveWithLines(Yii::$app->request->post())
            ) {
            if ($model->sendToStatus('issued')){
                $model->save();
                return $this->redirect([
                    '/site/digital-receipt',
                    'id' => $model->client_id,
                    ]);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'organizationalUnit' => $this->organizationalUnit,
        ]);
    }

    public function actionIssue($id)
    {
        if (!Yii::$app->request->isPost){
             throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        $model = $this->findModel($id);
        
        if ($model->sendToStatus('issued')){
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('app', 'The receipt has been successfully issued.'));

            return $this->redirect([
                '/site/digital-receipt',
                'id' => $model->client_id,
                ]);
        }
        
        Yii::$app->session->setFlash('error', Yii::t('app', 'The receipt could not be issued.'));
        return $this->redirect(['view', 'id' => $model->id]);
    }
    
    public function actionCheckItems($id)
    {
        $model = $this->findModel($id);
        $result = $model->getErrorsOnItemsSavedOnDB();
        $message = $result;
        $status = 'error';
        if (!$result)  {
            $status = 'success';
            $message = Yii::t('app', 'Everything looks fine');
        }
        Yii::$app->session->setFlash($status, $message);
        return $this->redirect(['view', 'id' => $model->id]);
    }
    
    /**
     * Updates an existing DigitalReceipt model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->fixContacts() && $model->save()) {
            if ($model->send())
            {
                $model->sent_at = time();
                $model->sendToStatus('sent');
                $model->save();
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'digitalReceiptType'=>$model->digitalReceiptType,
            'organizationalUnit' => $this->organizationalUnit,
        ]);
    }
    
    public function actionVoid($id)
    {
        return $this->actionChange($id, 'DigitalReceiptWorkflow/voided');
    }

    public function actionUnlinkFromExpo($id)
    {
        $model = $this->findModel($id);
        $model->expo_id = null;
        //$model->notes .= ' § ' . Yii::t('app', 'Unlinked from Expo');
        $model->save(false);
        LogHelper::log('unlinked Expo', $model, ['excluded'=>[
            'date',
            'wf_status',
            'organizational_unit_id',
            'digital_receipt_type_id',
            'tags',
            'total_amount',
            'email',
            'phone',
            'sent_at',
            'processed_at',
            'voided_at',
            'expo_id',
            'transaction_id',
            'client_id',
            'assigned_id',
            'voiding_receipt_assigned_id',
            'voided_receipt_assigned_id',
            'document_number',
            'parent_id',
            'cash_payment_amount',
            'electronic_payment_amount',
            'api_request',
            'api_response',
            'api_callback',
            'issuer_data',
            'notes',
            'receipt_year',
        ]]);
        Yii::$app->session->setFlash('success', Yii::t('app', 'The receipt has been unlinked from the Expo.'));
        return $this->redirect(['view', 'id'=>$id]);;
    }

    public function actionPdf($id)
    {
        $model = $this->findModel($id);
        
        // Check if PDF is attached
        if (!$model->hasPdf) {
            throw new \yii\web\NotFoundHttpException('PDF not found');
        }
        
        return $this->redirect(['/attachments/file/download', 'id'=>$model->files[0]->id, 'hash'=> $model->files[0]->hash]);
    }
    
    public function actionFetchPdf($id)
    {
        $model = $this->findModel($id);
        if (!$model->fetchPdf()) {
            Yii::$app->session->setFlash('error', 'The PDF could not be fetched.');
        }
        else {
            Yii::$app->session->setFlash('success', 'The PDF was fetched and stored as attachment.');
        }
        
        return $this->redirect(['view', 'id'=>$model->id]);
        
    }

    public function actionProcessReturn($id)
    {
        $receipt = $this->findModel($id);
        
        $items = $receipt->getDigitalReceiptLines()->select([
            'item_assigned_id', 
            'quantity'=> new Expression('quantity - quantity_returned'), 
            'description', 
            'unit_price', 
            'unit_discount as discount',
        ])->asArray()->all();
        
        /*

        * Note to myself: the problem with this approach is that we should sum up the quantities for children too...

        $items = $receipt->getDigitalReceiptLines()->select([
            'item_assigned_id', 
            'quantity'=> new Expression('SUM(signed_quantity)'), 
            'description', 
            'unit_price', 
            'discount'=> new Expression('discount / SUM(signed_quantity)'),
        ])
        ->groupBy(['item_assigned_id', 'description', 'unit_price', 'discount'])
        ->asArray()->all();
        */

        if (!$items) {
            throw new NotFoundHttpException(Yii::t('app', 'No items to process.'));
        }

        $model = new DigitalReceiptProcessReturnForm($receipt, $items);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // Logic to process the return with the API
            // Access data via $model->returnQuantities (e.g., [530742 => 1])
            $returnReceiptId = $receipt->processReturn($model->returnQuantities, $model->reason);
            if ($returnReceiptId>-1) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Return processed successfully.'));
                
                $returnReceipt = DigitalReceipt::find()->withId($returnReceiptId)->one();
                
                if ($returnReceipt->sendToStatus('issued')){
                    $returnReceipt->save();
                    return $this->redirect([
                        '/site/digital-receipt',
                        'id' => $returnReceipt->client_id,
                        ]);
                }
                return $this->redirect(['view', 'id' => $returnReceiptId]);
            }
            Yii::$app->session->setFlash('error', Yii::t('app', 'The return could not be processed.'));
            return $this->redirect(['view', 'id' => $receipt->id]);
        }

        return $this->render('process-return', [
            'model' => $model,
        ]);
    }

    public function actionFetchData($id)
    {
        $model = $this->findModel($id);
        $items = $model->fetchItems();

        if (!$items) {
            throw new NotFoundHttpException(Yii::t('app', 'No items to process.'));
        }

        return $this->render('basic-view', [
            'model' => $model,
            'items' => $items,
        ]);
    }

    public function actionProcessSale($id)
    {
        $model = $this->findModel($id);
        $processed = $model->processSale();

        Yii::$app->session->setFlash('success', Yii::t('app', 'Processed: '). $processed);

        return $this->redirect(['view', 'id'=>$model->id]);
    }

    public function actionApp()
    {
        return $this->redirect(['app/']);
    }

    public function actionChange($id, $status) // Changes the workflow status of a digital receipt
    {
        $model = $this->findModel($id, false);
        return $this->_changeWorkflowStatus($model, $status);
    }

    public function notSavingBulkActions() {
        return [];
    }   

    /**
     * Finds the DigitalReceipt model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DigitalReceipt the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = DigitalReceipt::find()->withId($id)->withOrganizationalUnitId($this->organizationalUnit->id)->one();
        
        if ($model) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
