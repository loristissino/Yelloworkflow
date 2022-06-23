<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\OrganizationalUnit;

/* @var $this yii\web\View */
/* @var $model app\models\PeriodicalReport */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs(
    "
    (function ($) {
        $('#invert_selection').click(function() {
            $('.ous').each(function(index, item) {
                item.checked = !item.checked;
            });
        });
    })(window.jQuery);
    ",
    \yii\web\View::POS_END,
    'transactions_view_js_code_end'
);

?>

<div class="periodical-report-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <div style="margin-bottom: 10px"><a href="#" id="invert_selection"><?=Yii::t('app', 'Invert selection') ?></a></div>

    <?php //= $form->field($model, 'organizational_unit_id')->textInput() ?>
    
    <?= $form->field($model, 'organizational_unit_ids')->checkboxList(
        $model->organizationalUnits,
        
        [
            'itemOptions' => [
                'checked' => 'checked',
                'class' => 'ous'
                ],
            'separator'=>'<br/>'
        ]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'begin_date')->input('date', ['class'=>'date-field form-control'])->hint(Yii::t('app', 'When the begin date is set equal to the end date, the periodical report will not accept transactions (useful for collection of documents, like inventory papers).')) ?>

    <?= $form->field($model, 'end_date')->input('date', ['class'=>'date-field form-control']) ?>

    <?= $form->field($model, 'due_date')->input('date', ['class'=>'date-field form-control']) ?>
    
    <?= $form->field($model, 'required_attachments')->textArea()->hint(Yii::t('app', 'Write the list of required attachments for this periodical report, one by line.')) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
