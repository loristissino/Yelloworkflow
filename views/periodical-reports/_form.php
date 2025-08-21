<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PeriodicalReport */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="periodical-report-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= \app\models\OrganizationalUnit::getDropdown($form, $model) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'begin_date')->textInput() ?>

    <?= $form->field($model, 'end_date')->textInput() ?>

    <?= $form->field($model, 'required_attachments')->textArea() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
