<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PetitionSignatureSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="petition-signature-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'petition_id') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'message') ?>

    <?php // echo $form->field($model, 'accepted_terms') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'confirmed_at') ?>

    <?php // echo $form->field($model, 'validated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
