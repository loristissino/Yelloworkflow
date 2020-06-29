<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Affiliation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="affiliation-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= \app\models\User::getDropdown($form, $model) ?>

    <?= \app\models\OrganizationalUnit::getDropdown($form, $model) ?>

    <?= \app\models\Role::getDropdown($form, $model) ?>

    <?= $form->field($model, 'rank')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
