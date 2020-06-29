<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Authorization */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="authorization-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'controller_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'action_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'method')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'type')->dropdownList(['@'=>'Logged-in users', '*'=>'All users', '-'=>'Specific user'], ['prompt'=>'Select one']) ?>

    <?= \app\models\User::getDropdown($form, $model) ?>

    <?= $form->field($model, 'begin_date')->textInput() ?>

    <?= $form->field($model, 'end_date')->textInput() ?>

    <?= \app\models\Role::getDropdown($form, $model) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
