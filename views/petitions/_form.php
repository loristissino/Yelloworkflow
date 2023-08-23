<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Petition */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="petition-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'target')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'introduction')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'picture_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'request')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'updates')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'promoted_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'wf_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'launched_at')->textInput() ?>

    <?= $form->field($model, 'expired_at')->textInput() ?>

    <?= $form->field($model, 'goal')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
