<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\OrganizationalUnit;

/* @var $this yii\web\View */
/* @var $model app\models\PeriodicalReport */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="periodical-report-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //= $form->field($model, 'organizational_unit_id')->textInput() ?>
    
    <?= $form->field($model, 'organizational_unit_ids')->checkboxList(
        $model->organizationalUnits,
        //OrganizationalUnit::getActiveOrganizationalUnitsAsArray(['rank' => SORT_ASC, 'name' => SORT_ASC]), 
        
        [
            'itemOptions' => [
                'checked' => 'checked',
                ],
            'separator'=>'<br/>'
        ]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'begin_date')->textInput() ?>

    <?= $form->field($model, 'end_date')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
