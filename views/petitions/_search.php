<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PetitionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="petition-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'slug') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'target') ?>

    <?= $form->field($model, 'introduction') ?>

    <?php // echo $form->field($model, 'picture_url') ?>

    <?php // echo $form->field($model, 'request') ?>

    <?php // echo $form->field($model, 'updates') ?>

    <?php // echo $form->field($model, 'wf_status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'launched_at') ?>

    <?php // echo $form->field($model, 'expired_at') ?>

    <?php // echo $form->field($model, 'goal') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
