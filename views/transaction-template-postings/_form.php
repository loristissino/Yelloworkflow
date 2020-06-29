<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TransactionTemplatePosting */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transaction-template-posting-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'transaction_template_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'rank')->textInput() ?>

    <?= \app\models\Account::getDropdown($form, $model) ?>

    <?= \app\models\TransactionTemplatePosting::getDebitCreditDropdown($form, $model) ?>
    
    <?= $form->field($model, 'amount')->textInput() ?>
    
    <?= $form->errorSummary($model) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
