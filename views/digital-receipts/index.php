<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DigitalReceiptSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $organizationalUnit->name . ' - ' . Yii::t('app', 'Digital Receipts');
$this->params['breadcrumbs'][] = $this->title;


$columns = [
    //['class' => 'yii\grid\SerialColumn'],

    //'id',
    [
        'label'=>Yii::t('app', 'Date'),
        'attribute'=>'date',
        'format'=>'raw',
        'value'=>function($data) {
            return Yii::$app->formatter->asDate($data['date']);
        },
    ],
    [
        'attribute'=>'digitalReceiptType.label',
        'label'=> Yii::t('app', 'Type'),
    ],
    //'user_id',
    /*
    [
        'attribute'=>'organizational_unit',
        'format'=>'raw',
        'value'=>'organizationalUnit.viewLink',
    ],
    */
    //'digital_receipt_type_id',
    //'tags',
    [
        'attribute' => Yii::t('app', 'Amount'),
        'format' => 'raw',
        'value' => 'formattedTotalAmount',
        'contentOptions' => function ($model, $key, $index, $column) {
            $textStyle = '';
            if ($model->isVoidOrVoiding) {
                $textStyle = '; text-decoration: line-through; color: red';
            }
            if ($model->isReturnReceipt) {
                $textStyle = '; color: #800080';
            }
            $options=[
                'class' => 'amount',
                'style' => 'background-color: '. $model->digitalReceiptType->color . $textStyle,
            ];
            return $options;
        },
        'headerOptions' => ['class' => 'amount'],
        'footerOptions' => ['class' => 'amount'],
    ],
    [
        'attribute' => Yii::t('app', 'Payment'),
        'format' => 'raw',
        'value' => 'paymentMethodWithUser',
    ],
    //'email:email',
    //'phone',
    //'created_at',
    //'updated_at',
    //'sent_at',
    //'transaction_id',
    //'client_id',
    //'document_number',
    [
        'attribute' => 'sequential_number',
        'label' => Yii::t('app', 'Sequential Number'),
        'value' => 'completeSequentialNumber',
    ],
    
    //'cash_payment_amount',
    //'electronic_payment_amount',
    //'api_response',
    //'wf_status',
    [
        'attribute'=>'wf_status',
        'format'=>'raw',
        'value'=>'workflowLabel',
    ],
    ['class' => 'yii\grid\ActionColumn',
        'template'=>'{view} {update} {issue} {void} {transaction}',
        'buttons'=>[
            'update' => function ($url, $model) {
                if (!$model->isUpdatable)
                    return null;
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    ]);
                },
            'issue' => function ($url, $model) {
                if ($model->hasBeenIssued)
                    return null;
                return Html::a('<span class="glyphicon glyphicon-open-file"></span>', $url, [
                        //'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                        'data-method' => 'post',
                    ]);
                },
            'void' => function ($url, $model) {
                if (!$model->isVoidable)
                    return null;
                return Html::a('<span class="glyphicon glyphicon-ban-circle"></span>', $url, [
                        'data-confirm' => Yii::t('app', 'Are you sure you want to void this digital receipt?'),
                        'data-method' => 'post',
                        'title' => Yii::t('app', 'Void'),
                    ]);
                },
            'transaction' => function ($url, $model) {
                if (!$model->transaction_id)
                    return null;
                if ($model->organizational_unit_id != Yii::$app->session->get('organizational_unit_id', 0))
                    return null;
                return Html::a('<span class="glyphicon glyphicon-book"></span>',  ['transaction-submissions/view', 'id'=>$model->transaction_id], [
                        'title' => Yii::t('app', 'Transaction'),
                    ]);
                },
        ]
    ]
];

if (Yii::$app->session->get('debug', false)) {
    array_push($columns, ...[
        [
            'attribute' => 'assigned_id',
            'format' => 'raw',
            'contentOptions' => function ($model, $key, $index, $column) {
                $options=[];
                if ($model->assigned_id == 'NO VALUE') {
                    $options=['style' => 'background-color: #FF0000; color: white'];
                }
                return $options;
            },
        ],
    ]);
}

?>
<div class="digital-receipt-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('/common_partials/_need_to_change_organizational_unit') ?>
    <p><strong><?= Yii::t('app', 'Issue receipts anytime, even offline: <a href="{url}">try the app</a>!', ['url'=>Url::toRoute(['/app'])]) ?></strong></p>

    <p>
        <?php foreach($digitalReceiptTypes as $drt): ?>
            <?= Html::a('➕ ' . $drt->label, ['create', 'type'=>$drt->id], ['class' => 'btn btn-success']) ?>
        <?php endforeach ?>
    </p>
    
    <?php if (Yii::$app->user->hasAuthorizationFor('periodical-reports-management')): ?>
        <p>
            <?= Html::a(Yii::t('app', 'Manage All Receipts'), ['manage'], ['class' => 'btn btn-info']) ?>
        </p>
    <?php endif ?>
    
    <?php if ($expo = $organizationalUnit->currentExpo): ?>
        <p><?= Yii::t('app', 'The expo «{expo}» is currently running. Receipts will be linked to this event.', ['expo'=>$expo]) ?></p>
    <?php endif ?>
    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>


</div>
