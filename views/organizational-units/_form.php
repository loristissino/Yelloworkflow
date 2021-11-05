<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrganizationalUnit */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="organizational-unit-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rank')->textInput() ?>

    <?= $form->field($model, 'status')->checkbox() ?>
    
    <?= $form->field($model, 'last_designation_date')->input('date', ['class'=>'date-field form-control']) ?>
    
    <?= $form->field($model, 'notes')->textarea(['rows' => 6])->hint(Yii::t('app', 'Markdown allowed.')) ?>

    <?= $form->field($model, 'ceiling_amount')->textInput(['maxlength' => true]) ?>

    TODO: use checkboxes to set these possibilities<br />
    <?= $form->field($model, 'possible_actions')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
