<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TransactionTemplate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transaction-template-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= \app\models\OrganizationalUnit::getDropdown($form, $model, ['prompt'=>Yii::t('app', 'Common to every Organizational Unit')]) ?>

    <?= $form->field($model, 'status')->checkbox() ?>

    <?= $form->field($model, 'rank')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'o_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'o_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'needs_attachment')->checkbox() ?>

    <?= $model->getTernarianRadioList($form, ['field_name'=>'needs_project']) ?>

    <?= $form->field($model, 'needs_vendor')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
