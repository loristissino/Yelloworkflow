<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PetitionSignature */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="petition-signature-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'petition_id')->textInput() ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'yob')->textInput() ?>

    <?= $form->field($model, 'district')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gender')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'accepted_terms')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'confirmed_at')->textInput() ?>

    <?= $form->field($model, 'reminded_at')->textInput() ?>

    <?= $form->field($model, 'validated_at')->textInput() ?>
    
    <?= $form->errorSummary($model) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
