<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProjectComment */
/* @var $form yii\widgets\ActiveForm */

$statuses = array_keys($model->object->getWorkflow()->getAllStatuses());
$statuses = array_combine($statuses, $statuses);

?>

<div class="project-comment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'model')->textInput(['disabled' => true]) ?>

    <?= $form->field($model, 'id')->textInput(['disabled' => true]) ?>

    <?= $form->field($model, 'status')->dropdownList($statuses) ?>

    <?= $form->field($model, 'description')->textInput()->hint(Yii::t('app', 'The reason for the extraordinary change that will be written in the log')) ?>

    <?= $form->errorSummary($model) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
