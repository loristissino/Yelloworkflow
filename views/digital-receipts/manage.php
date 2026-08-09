<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DigitalReceiptSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Digital Receipts Management');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Digital Receipts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Management');

$columns = [
    ['class' => 'yii\grid\CheckboxColumn'],
    [
        'label'=>Yii::t('app', 'Date'),
        'attribute'=>'date',
        'format'=>'raw',
        'value'=>function($data) {
            return Yii::$app->formatter->asDate($data['date']);
        },
    ],

    [
        'attribute'=>'digitalReceiptType.sequential_number_code',
        'label'=> Yii::t('app', 'Type'),
    ],
    [
        'attribute'=>'organizational_unit',
        'format'=>'raw',
        'value'=>'organizationalUnit.viewLink',
        'label'=> Yii::t('app', 'Organizational Unit'),
    ],
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
        'value' => 'paymentMethod',
    ],
    [
        'attribute' => 'sequential_number',
        'label' => Yii::t('app', 'Sequential Number'),
        'value' => 'completeSequentialNumber',
    ],
    
    //'wf_status',
    [
        'attribute'=>'wf_status',
        'format'=>'raw',
        'value'=>'workflowLabel',
    ],

    ['class' => 'yii\grid\ActionColumn',
        'template'=>'{show} {workflow}',
        'buttons'=>[
            'show' => function ($url, $model) {
                $html = Html::a('<span class="glyphicon glyphicon-list-alt"></span>', ['site/digital-receipt', 'id'=>$model->client_id], ['target'=>'_blank', 'title'=>Yii::t('app', 'Show Details')]); 
                if ($model->transaction_id) {
                    $html .= ' ' . 
                        Html::a('<span class="glyphicon glyphicon-book"></span>', ['fast-transactions/view', 'id'=>$model->transaction_id], ['title'=>Yii::t('app', 'Transaction')]);
                }
                return $html;
            },
            'workflow' => function ($url, $model) {
                if (Yii::$app->user->hasAuthorizationFor('workflow')) {
                    $icon = 'glyphicon glyphicon-calendar';
                    return Html::a(sprintf('<span class="%s" style="color:#800080" title="%s"></span>', $icon, Yii::t('app', 'Edit Workflow Status')), ['workflow/update', 'type'=>get_class($model), 'id'=>$model->id, 'return'=>Url::current()]);
                }
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

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?=Html::beginForm(['process'],'post');?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>

    <?= Yii::t('app', 'With the selected digital receipts: ') ?>
    <?= Html::a(Yii::t('app', 'Mark as Posted'), ['process', 'action'=>'markAsPosted', 'redirect'=>'manage'], ['data-method'=>'post'])?>
    <?= Html::endForm();?>

</div>
