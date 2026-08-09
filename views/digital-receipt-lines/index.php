<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DigitalReceiptLineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$allModels = $dataProvider->getModels();

?>

<h2><?= Yii::t('app', 'Takings') ?></h2>

<div class="digital-receipt-line-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'digital_receipt_id',
                'format' => 'raw',
                'value' => function($model, $key, $index, $column) use($allModels) {
                    if ($index === 0 || $allModels[$index - 1]->digital_receipt_id !== $model->digital_receipt_id) {
                        return Html::a($model->digitalReceipt->completeSequentialNumber, ['/site/digital-receipt', 'id'=>$model->digitalReceipt->client_id], ['target'=>'_blank']);
                    }
                    return '';
                }
            ],
            [
                'label'=>Yii::t('app', 'Workflow Status'),
                'format'=>'raw',
                'value' => function($model, $key, $index, $column) use($allModels) {
                    if ($index === 0 || $allModels[$index - 1]->digital_receipt_id !== $model->digital_receipt_id) {
                        return $model->digitalReceipt->workflowLabel;
                    }
                    return '';
                }
            ],
            [
                'attribute' => Yii::t('app', 'Payment'),
                'format' => 'raw',
                'value' => function($model, $key, $index, $column) use($allModels) {
                    if ($index === 0 || $allModels[$index - 1]->digital_receipt_id !== $model->digital_receipt_id) {
                        return $model->digitalReceipt->paymentMethod;
                    }
                    return '';
                }
            ],
            [
                'attribute' => 'description',
                'format' => 'raw',
            ],
            [
                'attribute' => Yii::t('app', 'Amount'),
                'format' => 'raw',
                'value' => 'formattedAmount',
                'contentOptions' => function ($model, $key, $index, $column) {
                    $textStyle = '';
                    if ($model->digitalReceipt->isVoidOrVoiding) {
                        $textStyle = '; text-decoration: line-through; color: red';
                    }
                    if ($model->digitalReceipt->isReturnReceipt) {
                        $textStyle = '; color: #800080';
                    }
                    $options=[
                        'class' => 'amount',
                        'style' => 'background-color: '. $model->digitalReceipt->digitalReceiptType->color . $textStyle,
                    ];
                    return $options;
                },
                'headerOptions' => ['class' => 'amount'],
                'footerOptions' => ['class' => 'amount'],
            ],
            
            'notes',
        ],
    ]); ?>


</div>
