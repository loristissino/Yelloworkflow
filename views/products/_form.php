<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= \app\models\DigitalReceiptType::getDropdown($form, $model) ?>

    <?= \app\models\OrganizationalUnit::getDropdown($form, $model)->hint(Yii::t('app', 'By default, all organizational units can sell this product. Specify one here to limit sales to that unit.')) ?>
    
    <?= $form->field($model, 'sku')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ecommerce_code')->textInput(['maxlength' => true]) ?>

     <?= $form->field($model, 'status')->checkbox() ?>

    <?= $form->field($model, 'rank')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'long_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'unit_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'max_discount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'standard_discount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'internal_discount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vat_rate_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'notes')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'extra_info_required')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'requires_sealing')->checkbox()->hint(Yii::t('app', 'Creates automatically a sealed transaction when used.')) ?>

    <?= \app\models\Account::getDropdown($form, $model, ['field_name'=>'sales_account_id']) ?>

    <?= \app\models\Account::getDropdown($form, $model, ['field_name'=>'discounts_account_id']) ?>

    <?= \app\models\Account::getDropdown($form, $model, ['field_name'=>'returns_account_id']) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
