<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProjectComment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="project-comment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6, 'autofocus'=>true, 'onfocus'=>'this.setSelectionRange(this.value.length,this.value.length);']) ?>

    <?php if($controller=='projects-management' and $model->isNewRecord and $project->hasWfStatus('submitted')): ?>
        <?= $form->field($model, 'immediately_question_project')->checkbox()->hint(Yii::t('app', 'By checking this box, the project will be immediately set to "questioned". Use it when there is only one comment.')) ?>
    <?php endif ?>

    <?= $form->field($model, 'immediately_notify_comment')->checkbox()->hint(Yii::t('app', 'By checking this box, the comment will be immediately notified to the concerned users. Otherwise, it will be notified at the workflow change of the project.')) ?>

    <?= $form->errorSummary($model) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
