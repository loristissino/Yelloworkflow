<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DigitalReceiptSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="digital-receipt-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'wf_status') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'organizational_unit_id') ?>

    <?php // echo $form->field($model, 'digital_receipt_type_id') ?>

    <?php // echo $form->field($model, 'tags') ?>

    <?php // echo $form->field($model, 'total_amount') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'sent_at') ?>

    <?php // echo $form->field($model, 'transaction_id') ?>

    <?php // echo $form->field($model, 'client_id') ?>

    <?php // echo $form->field($model, 'assigned_id') ?>

    <?php // echo $form->field($model, 'document_number') ?>

    <?php // echo $form->field($model, 'cash_payment_amount') ?>

    <?php // echo $form->field($model, 'electronic_payment_amount') ?>

    <?php // echo $form->field($model, 'api_response') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
