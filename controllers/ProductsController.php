<?php

namespace app\controllers;

use Yii;
use app\models\Product;
use app\models\ProductSearch;
use app\models\DigitalReceiptLine;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii\filters\VerbFilter;
use app\components\CController;
/**
 * ProductsController implements the CRUD actions for Product model.
 */
class ProductsController extends CController
{

    use SubmissionsTrait;

    public function beforeAction($action)
    {
        $this->setOrganizationalUnit($action);
        if (!$this->organizationalUnit) {
            $this->redirect(['/site/choose-organizational-unit', 'return'=>\Yii::$app->request->url]);
            return;
        }
     
        return parent::beforeAction($action);
    }


    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex($pagesize=100)
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(
            Yii::$app->request->queryParams,
            Product::find()->salableByOrganizationalUnit($this->organizationalUnit)
        );
        
        $dataProvider->sort->defaultOrder = ['rank'=> SORT_ASC, 'description' => SORT_ASC];

        $dataProvider->pagination = [
            'pageSize' => $pagesize,
        ];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }



    public function actionManage($pagesize=100)
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $dataProvider->pagination = [
            'pageSize' => $pagesize,
        ];

        return $this->render('manage', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }



    /**
     * Displays a single Product model.
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

    public function actionDetails($id)
    {
        return $this->render('details', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
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

    public function actionSearchProduct($term)
    {
        $digitalReceiptType = Yii::$app->session->get('digitalReceiptType');
        // 1. Search Logic
        $products = Product::find()
            ->active()
            ->where(['or',
                ['like', 'description', $term],
                ['like', 'author', $term],
                ['like', 'isbn', $term]
            ])
            // Optional: Filter by type if your logic requires it
            ->andWhere(['digital_receipt_type_id' => $digitalReceiptType->id])
            ->limit(20)
            ->all();

        $result = [];

        foreach ($products as $product) {
            $result[] = [
                'sku' => $product->sku,
                'isbn' => $product->isbn,
                'author' => $product->author,
                'label' => $product->description . ' label',
                'value' => $product->description . ' value',
                'price' => $product->unit_price,
                'standard_discount' => $product->standard_discount,
                'notes' => $product->notes,
                'id'    => $product->id,
            ];
        }

        return JSON_encode($result);

    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Product::find()->withId($id)->one();
        
        if (!$model) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
        
        if (!$model->isSalableByOrganizationalUnit($this->organizationalUnit) && !Yii::$app->user->hasAuthorizationFor('products')) {
            throw new UnauthorizedHttpException(Yii::t('app', 'You are not authorized to see this page.'));
        }
        
        return $model;
    }
}
