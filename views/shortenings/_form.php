<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Shortener */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="petition-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if ($model->multiline): ?>

        <?= $form->field($model, 'url')->textArea(['cols' => 60, 'rows'=>10])->label(Yii::t('app', 'URLs'))->hint(Yii::t('app', 'Enter URLs in the format <strong><em>long URL</em> [TAB] <em>custom short alias</em></strong>, one pair per line. You can copy & paste directly from a spreadsheet. If you leave the <em>custom short alias</em> empty a random one will be generated.')) ?>
    
    <?php else: ?>

        <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

        <?php /*= $form->field($model, 'title')->textInput(['maxlength' => true]) */ ?>

        <?= $form->field($model, 'keyword')->textInput(['maxlength' => true])->hint(Yii::t('app', 'Leave empty for autogeneration')) ?>

    <?php endif ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
