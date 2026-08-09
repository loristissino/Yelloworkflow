<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DigitalReceiptSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

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
    
    [
        'attribute'=>'wf_status',
        'format'=>'raw',
        'value'=>'workflowLabel',
    ],

    ['class' => 'yii\grid\ActionColumn',
        'template'=>'{view} {print} {journalize-separately}',
        'buttons'=>[
            'view' => function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-list-alt"></span>', ['/site/digital-receipt', 'id'=>$model->client_id], [
                    'title'=>Yii::t('app', 'View'),
                    ]);
                },
            'print' => function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-print"></span>', ['/site/digital-receipt', 'id'=>$model->client_id, 'format'=>'pdf'], [
                    'target'=>'_blank',
                    'title'=>Yii::t('app', 'PDF / Print'),
                    ]);
                },
            'journalize-separately' => function ($url, $model) {
                if ($model->canBeJournalizedSeparately){
                    return Html::a('<span class="glyphicon glyphicon-transfer"></span>', ['/transaction-submissions/journalize-separately', 'receipt'=>$model->id], [
                        'title'=>Yii::t('app', 'Create a separate transaction for this specific receipt'),
                        ]);
                    }
                return '';
                }
            ],
    ],
    
];

?>
<div class="digital-receipt-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => null,
        'columns' => $columns,
    ]); ?>

</div>
