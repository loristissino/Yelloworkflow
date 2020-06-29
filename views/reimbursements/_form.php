<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Reimbursement */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reimbursement-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'project_id')->textInput() ?>

    <?= $form->field($model, 'wf_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'requested_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'request_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reimbursed_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reimbursement_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
