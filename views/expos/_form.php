<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Expo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="expo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'begin_date')->input('date', ['class'=>'date-field form-control']) ?>

    <?= $form->field($model, 'end_date')->input('date', ['class'=>'date-field form-control']) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

    <?= \app\models\OrganizationalUnit::getDropdown($form, $model) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
