<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Shortener */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="petition-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?php /*= $form->field($model, 'title')->textInput(['maxlength' => true]) */ ?>

    <?= $form->field($model, 'keyword')->textInput(['maxlength' => true])->hint(Yii::t('app', 'Leave empty for autogeneration')) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
