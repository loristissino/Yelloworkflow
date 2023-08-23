<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Account */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="account-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= \app\models\OrganizationalUnit::getDropdown($form, $model) ?>

    <?= $form->field($model, 'rank')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reversed_name')->textInput(['maxlength' => true])->hint(Yii::t('app', 'Used for accounts that must be shown differently from the central point of view.')) ?>

    <?= $form->field($model, 'status')->checkbox() ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
    
    <?= \app\models\Account::getPossibleWaysOfBeingShownInOUViewDropdown($form, $model) ?>

    <?= $form->field($model, 'debits_header')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'credits_header')->textInput(['maxlength' => true]) ?>

    <?= \app\models\Account::getPossibleTypesDropdown($form, $model) ?>

    <?= \app\models\Account::getPossibleEnforcedBalancesDropdown($form, $model) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
