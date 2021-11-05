<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use zhuravljov\yii\widgets\DatePicker;

use nemmo\attachments\components\AttachmentsInput;
use app\assets\TransactionFormAsset;

TransactionFormAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */
/* @var $form yii\widgets\ActiveForm */

$templates = JSON_encode($model->templates);

$vat_number_messages = [
    'ok' => Yii::t('app', 'VAT Number seems to be OK.'),
    'wrong' => Yii::t('app', 'VAT Number seems to be wrong.'),
];

$this->registerJs(
    "
    let templates = JSON.parse(".\yii\helpers\Json::htmlEncode($templates).");
    let vat_number_messages = JSON.parse('".\yii\helpers\Json::htmlEncode($vat_number_messages)."');
    ",
    \yii\web\View::POS_HEAD,
    'transaction_js_code_head'
);

?>

<div class="transaction-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    
    <?php if ($office_transaction): ?>
        <?= \app\models\OrganizationalUnit::getDropdown($form, $model, ['possible_actions' => \app\models\OrganizationalUnit::HAS_OWN_CASH]) ?>
    <?php endif ?>

    <?= \app\models\TransactionTemplate::getDropdown($form, $model, ['array'=>$model->templates]) ?>
    
    <div class="info-block" style="display:none" id="template_description"></div>

	<?= $form->field($model, 'date')->widget(DatePicker::class, [
		'clientOptions' => [
			'format' => 'yyyy-mm-dd',
			'language' => Yii::$app->language,
			'autoclose' => true,
			'startDate' => $model->begin_date,
			'endDate' => $model->end_date,
		],
		'clientEvents' => [],
	]) ?> 

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'amount')->textInput(['maxlength' => true, 'size' => 10])->hint(Yii::t('app', 'Use a dot for the decimal part of the amount.')) ?>

    <?= $form->field($model, 'notes')->textInput(['maxlength' => true]) ?>

    <?php if (! $office_transaction): ?>
    <fieldset id="project_fieldset">
        <legend><?= Yii::t('app', 'Project') ?></legend>
        <?= \app\models\Project::getDropdown($form, $model, ['organizational_unit_id'=>Yii::$app->controller->periodicalReport->organizational_unit_id]) ?>

        <?php //= $form->field($model, 'event_id')->textInput() ?>
    </fieldset>
    <?php endif ?>

    <fieldset id="vendor_fieldset">
        <legend><?= Yii::t('app', 'Vendor') ?></legend>
        <?= $form->field($model, 'vat_number')->textInput(['maxlength' => true]) ?>
        <div class="info-block" style="display:none" id="vat_number_check"></div>
        <?= $form->field($model, 'vendor')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'invoice')->textInput(['maxlength' => true]) ?>
    </fieldset>

    <fieldset id="attachments_fieldset">
        <legend><?= Yii::t('app', 'Attachments') ?></legend>
        <?= AttachmentsInput::widget([
            // see https://github.com/Nemmo/yii2-attachments
            'id' => 'file-input', // Optional
            'model' => $model,
            'options' => [ // Options of the Kartik's FileInput widget
                'multiple' => true, // If you want to allow multiple upload, default to false
            ],
            'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget 
                // see https://plugins.krajee.com/file-basic-usage-demo
                'maxFileCount' => 3, // Client max files
                'initialPreview' => $model->isNewRecord ? [] : $model->getInitialPreview(),
                'dropZoneEnabled' => true,
                'showPreview' => false,
                'allowedFileExtensions' => ['png', 'pdf', 'jpeg', 'jpg'],
            ]
        ]) ?>
    </fieldset>

    <?= $form->errorSummary($model) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success', 'id'=>'save_button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
