<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrganizationalUnit */

$this->title = Yii::t('app', 'Organizational Units\'s Ceiling Amounts');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Organizational Units'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Ceiling Amounts');
?>
<div class="organizational-units-plafonds">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="plafonds-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'values')->textArea(['maxlength' => true, 'cols'=>2, 'rows'=>10])->hint(Yii::t('app', 'You may copy and paste data to a spreadsheet, edit them and then paste them back again here.')) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Update Ceiling Amounts'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
