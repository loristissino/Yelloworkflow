<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="expense-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'vat_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor')->textInput(['maxlength' => true]) ?>
    
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
