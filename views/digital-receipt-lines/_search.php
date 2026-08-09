<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DigitalReceiptLineSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="digital-receipt-line-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'digital_receipt_id') ?>

    <?= $form->field($model, 'product_id') ?>

    <?= $form->field($model, 'sku') ?>

    <?= $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'unit_price') ?>

    <?php // echo $form->field($model, 'quantity') ?>

    <?php // echo $form->field($model, 'discount') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'vat_rate_code') ?>

    <?php // echo $form->field($model, 'notes') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
