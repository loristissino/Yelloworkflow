<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Role */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="role-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'rank')->textInput() ?>
    
    <?= $model->getTernarianDropdown($form, ['field_name'=>'status']) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'permissions')->textInput(['maxlength' => true])->hint(Yii::t('app', 'For controllers behind a path, use backslashes (eg. api\v1\users).')) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true])->hint(Yii::t('app', 'Use the string "ou" to mean that notifications should go to the email of the organizational unit.')) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
