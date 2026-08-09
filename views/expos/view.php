<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\DigitalReceiptLine;
use app\models\DigitalReceiptLineSearch;
use app\models\Transaction;
use app\models\TransactionSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Expo */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Expos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$transitions = $model->getAuthorizedTransitions();
$currentStatus = $model->getWorkflowStatus()->getId();

$digitalReceiptLinesSearchModel = new DigitalReceiptLineSearch();
$digitalReceiptLinesDataProvider = $digitalReceiptLinesSearchModel->search(Yii::$app->request->queryParams, DigitalReceiptLine::find()->linkedToExpo($model->id));
$digitalReceiptLinesDataProvider->sort = false;
$digitalReceiptLinesDataProvider->pagination = [
    'pageSize' => 1000,
];

$salesDataProvider = $model->getSales();

$recappedTransactionsDataProvider = $model->getRecappedTransactions();

$adjustmentsDataProvider = $model->getAdjustments();

?>
<div class="expo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

        <?= $this->render('/workflow/_workflowbuttons', [
            'model' => $model,
            'transitions' => $transitions
        ]) ?>
        
        <?= Html::a(Yii::t('app', 'Clone'), ['clone', 'id' => $model->id], [
            'class' => 'btn btn-info',
            'data' => [
                'method' => 'post',
            ],
        ]) ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'begin_date',
            'end_date',
            'name',
            'city',
            [
                'attribute'=>'organizational_unit_id',
                'format'=>'raw',
                'value'=>$model->organizationalUnit,
            ],
            'wf_status',
            [
                'attribute' => 'periodical_report_id',
                'format' => 'raw',
                'value' => $model->periodical_report_id ? $model->periodicalReport->getViewLink(['controller'=>'periodical-reports-management']) : null,
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

    <?= $this->render('/digital-receipt-lines/index', [
        'searchModel' => null, //$digitalReceiptLinesSearchModel,
        'dataProvider' => $digitalReceiptLinesDataProvider,
        'model' => $model,
    ]);
    ?>

    <h2><?= Yii::t('app', 'Transactions') ?></h2>
    <p><?= Yii::t('app', 'Only generated transactions concerning sales are shown here.') ?></p>
    <?= GridView::widget([
        'dataProvider' => $salesDataProvider,
        'filterModel' => null,
        'columns' => [
            [
                'attribute' => 'date',
                'label' => Yii::t('app', 'Date'),
                'format' => 'raw',
                    /*'value' => function($row){
                    return Html::a($row['transaction_id'], ['fast-transactions/view', 'id'=>$row['transaction_id']]);
                }*/
            ],
            [
                'attribute' => 'transaction_id',
                'label' => Yii::t('app', 'Transaction'),
                'format' => 'raw',
                'value' => function($row){
                    return Html::a($row['transaction_id'], ['fast-transactions/view', 'id'=>$row['transaction_id']]);
                }
            ],
            [
                'attribute' => 'account_name',
                'label' => Yii::t('app', 'Account'),
                'format' => 'raw',
            ],
            [
                'attribute' => 'account_id',
                'label' => Yii::t('app', 'Account ID'),
                'format' => 'raw',
            ],
            [
                'attribute' => 'parent_account_id',
                'label' => Yii::t('app', 'Parent Account ID'),
                'format' => 'raw',
            ],
            [
                'attribute' => 'payment_method',
                'label' => Yii::t('app', 'Payment'),
                'format' => 'raw',
                'value' => function($row) {
                    return Yii::t('app', $row['payment_method']);
                }
            ],
            [
                'attribute' => 'amount',
                'label' => Yii::t('app', 'Amount'),
                'format' => 'raw',
                'value' => function($row) {
                    return Yii::$app->formatter->asCurrency($row['amount']);
                },
                'contentOptions' => ['class' => 'amount'],
                'headerOptions' => ['class' => 'amount'],
                'footerOptions' => ['class' => 'amount'],
            ],
            
        ],
    ]); ?>

    <h2><?= Yii::t('app', 'Recap') ?></h2>
    <?= GridView::widget([
        'dataProvider' => $recappedTransactionsDataProvider,
        'filterModel' => null,
        'columns' => [
            [
                'attribute' => 'account_name',
                'label' => Yii::t('app', 'Account'),
                'format' => 'raw',
            ],
            [
                'attribute' => 'account_id',
                'label' => Yii::t('app', 'Account ID'),
                'format' => 'raw',
            ],
            [
                'attribute' => 'parent_account_id',
                'label' => Yii::t('app', 'Parent Account ID'),
                'format' => 'raw',
            ],
            [
                'attribute' => 'payment_method',
                'label' => Yii::t('app', 'Payment'),
                'format' => 'raw',
                'value' => function($row) {
                    return Yii::t('app', $row['payment_method']);
                }
            ],
            [
                'attribute' => 'total_amount',
                'label' => Yii::t('app', 'Total Amount'),
                'format' => 'raw',
                'value' => function($row) {
                    return Yii::$app->formatter->asCurrency($row['total_amount']);
                },
                'contentOptions' => ['class' => 'amount'],
                'headerOptions' => ['class' => 'amount'],
                'footerOptions' => ['class' => 'amount'],
            ],
            
        ],
    ]); ?>

    <?php if ($model->getWorkflowStatus()->getId()!='ExpoWorkflow/closed'): ?>
        <h2><?= Yii::t('app', 'Adjustments') ?></h2>
        <p><?= Yii::t('app', 'These adjustments will be carried out when the Expo is marked closed.') ?></p>
        <?= GridView::widget([
            'dataProvider' => $adjustmentsDataProvider,
            'filterModel' => null,
            'columns' => [
                [
                    'attribute' => 'account_name',
                    'label' => Yii::t('app', 'Account'),
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'account_id',
                    'label' => Yii::t('app', 'Account ID'),
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'new_account_name',
                    'label' => Yii::t('app', 'New Account'),
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'total_amount',
                    'label' => Yii::t('app', 'Amount'),
                    'format' => 'raw',
                    'value' => function($row) {
                        return Yii::$app->formatter->asCurrency($row['total_amount']);
                    },
                    'contentOptions' => ['class' => 'amount'],
                    'headerOptions' => ['class' => 'amount'],
                    'footerOptions' => ['class' => 'amount'],
                ],
                
            ],
        ]); ?>
    <?php endif ?>


</div>
