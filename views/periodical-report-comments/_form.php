<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PeriodicalReportComment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="periodical-report-comment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>
    
    <?php if($controller=='periodical-reports-management' and $model->isNewRecord and ($periodicalReport->isSubmitted or $periodicalReport->isSubmittedEmpty)): ?>
        <?= $form->field($model, 'immediately_question_periodical_report')->checkbox()->hint(Yii::t('app', 'By checking this box, the periodical report will be immediately set to "questioned". Use it when there is only one comment.')) ?>
    <?php endif ?>

    <?= $form->errorSummary($model) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
