<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->hiddenInput(['maxlength' => true])->label(false) ?>

    <?= $form->field($model, 'first_name')->hiddenInput(['maxlength' => true])->label(false) ?>

    <?= $form->field($model, 'last_name')->hiddenInput(['maxlength' => true])->label(false) ?>

    <?= $form->field($model, 'email')->hiddenInput(['maxlength' => true])->label(false) ?>

    <?= $form->field($model, 'auth_key')->textInput(['maxlength' => true]) ?>
    
    <?php //= $form->field($model, 'access_token')->textInput(['maxlength' => true]) ?>

    <?php //= $form->field($model, 'otp_secret')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->hiddenInput()->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Reset Password'), ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
