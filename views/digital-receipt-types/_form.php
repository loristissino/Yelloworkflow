<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DigitalReceiptType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="digital-receipt-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'explanation')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'label')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'issued_text')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'voiding_text')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'return_text')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sequential_number_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'amount_soft_limit')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'amount_hard_limit')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'color')->textInput(['type'=>'color']) ?>

    <?= $form->field($model, 'validator')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'environment')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
