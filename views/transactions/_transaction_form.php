<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
//use zhuravljov\yii\widgets\DatePicker;
use yii\jui\AutoComplete;

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

$date_field_message = Yii::t('app', 'Your browser does not seem to support date picking. Write the date using the format <em>yyyy-mm-dd</em>.');"Il tuo browser non sembra supportare la scelta delle date: scrivile usando il formato <em>anno-mese-giorno</em>";

$this->registerJs(
    "
    let templates = JSON.parse(".\yii\helpers\Json::htmlEncode($templates).");
    let vat_number_messages = JSON.parse('".\yii\helpers\Json::htmlEncode($vat_number_messages)."');
    
    let projectsUrl = '". Url::toRoute(['office-transactions/projects', 'organizational_unit_id'=>'ou_id']). "';
    let ouAccountingSummaryUrl = '". Url::toRoute(['office-transactions/view-ou-accounting-summary', 'id'=>'ou_id']). "';
    
    let projectPrompt = '<option value=\'\'>". Yii::t('app', 'Choose the project') . "</option>';
    
    async function updateProjects() {
        let ou_id = $('#" . Html::getInputId($model, 'organizational_unit_id') . "').val();
        let url = projectsUrl.replace('ou_id', ou_id);
        let response = await fetch(url);
        let projects = await response.json();
        let options = [];
        let projectId = " . ($model->project_id ? $model->project_id : 0) . ";
        for (const id in projects) {
            let opt = '<option ';
            if (id == projectId) {
                opt += 'selected ';
            }
            opt += 'value=\'' + id + '\'>'+ projects[id] + '</option>';
            console.log(opt);
            options.push(opt);
        }
        $('#" . Html::getInputId($model, 'project_id') . "').html(projectPrompt + options.join(''));
        
        await fetchOuAccountingSummary(ou_id);
    }
    
    async function fetchOuAccountingSummary(ou_id)
    {
        $('#loader').show();
        $('#ou_info').hide();
        let url = ouAccountingSummaryUrl.replace('ou_id', ou_id);
        let response = await fetch(url);
        let text = await response.text();
        $('#ou_info').html(text);
        $('#ou_info').show();
        $('#loader').hide();
    }
    
    ",
    \yii\web\View::POS_HEAD,
    'transaction_js_code_head'
);

if ($model->organizational_unit_id) {
    $this->registerJs(
        "
            fetchOuAccountingSummary(" . $model->organizational_unit_id . ");
        ",
        \yii\web\View::POS_READY,
        'transaction_js_code_ready'
    );
}

$this->registerJs(
    "
    
    function isDateSupported() {
        var input = document.createElement('input');
        var value = 'a';
        input.setAttribute('type', 'date');
        input.setAttribute('value', value);
        return (input.value !== value);
    };
    
    if (!isDateSupported()) {
        document.querySelectorAll('.date-field').forEach((item)=>{console.log(item); item.type='text'; item.placeholder='aaaa-mm-gg';});
        document.querySelector('#html5info').innerHTML = '" . $date_field_message . "';
        document.querySelector('#html5info').style = 'padding: 10px; background-color: #FFD78F';
    }

    ",
    \yii\web\View::POS_READY,
    'not_html5_date_ready_fix'
);


$allowedAttachmentExtensions = ['png', 'pdf', 'jpeg', 'jpg'];

$showAlsoEndedProjects = false;

if (Yii::$app->controller->id == 'office-transactions') {
    $allowedAttachmentExtensions[] = 'ods';
    $allowedAttachmentExtensions[] = 'xlsx';
    $showAlsoEndedProjects = true;
}

?>

<div class="transaction-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    
    <?php if ($office_transaction): ?>
        <?= \app\models\OrganizationalUnit::getDropdown($form, $model, [
            'possible_actions' => \app\models\OrganizationalUnit::HAS_OWN_CASH,
            'onchange' => 'updateProjects()',
            ]) ?>
        <div style="padding-bottom: 16px">
            <span id="ou_info"></span>
            <img style="display: none" id="loader" src="<?= Url::to('@web/images/submit_loader.gif') ?>" />
        </div>
    <?php endif ?>

    <?= \app\models\TransactionTemplate::getDropdown($form, $model, ['array'=>$model->templates]) ?>
    
    <div class="info-block" style="display:none" id="template_description"></div>

    <div id="html5info"></div>
    <?= $form->field($model, 'date')->input('date', [
            'class'=>'date-field form-control',
            'min' => $model->begin_date,
            'max' => $model->end_date,
        ]) ?>

	<?php /*= $form->field($model, 'date')->widget(DatePicker::class, [
		'clientOptions' => [
			'format' => 'yyyy-mm-dd',
			'language' => Yii::$app->language,
			'autoclose' => true,
			'startDate' => $model->begin_date,
			'endDate' => $model->end_date,
		],
		'clientEvents' => [],
	]) */?> 

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'amount')->textInput(['maxlength' => true, 'size' => 10])->hint(Yii::t('app', 'Use a dot for the decimal part of the amount.')) ?>

    <div class="info-block alert" style="display:none" id="notes_request"></div>
    <?= $form->field($model, 'notes')->textInput(['maxlength' => true]) ?>

    <fieldset id="project_fieldset">
        <legend><?= Yii::t('app', 'Project') ?></legend>
        <?= \app\models\Project::getDropdown($form, $model, ['organizational_unit_id'=>Yii::$app->controller->periodicalReport ? Yii::$app->controller->periodicalReport->organizational_unit_id: 0, 'alsoEnded'=>$showAlsoEndedProjects]) ?>
    </fieldset>

    <fieldset id="vendor_fieldset">
        <legend><?= Yii::t('app', 'Vendor') ?></legend>
        
        <?= $form->field($model, 'vat_number')->widget(\yii\jui\AutoComplete::classname(), [
            'options' => [
                'class'=>'form-control',
            ],
            'clientOptions' => [
                'source' => \app\models\Transaction::getKnownVATNumbers(),
            ],
        ])->hint(Yii::t('app', 'You can omit the VAT number, but if it is present it must be correct.'))
         ?>
        
        <div class="info-block" style="display:none" id="vat_number_check"></div>
        <?= $form->field($model, 'vendor')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'invoice')->textInput(['maxlength' => true])->hint(Yii::t('app', 'Number and date of the invoice or description of the document (for instance, "Receipt number X").')) ?>
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
                'allowedFileExtensions' => $allowedAttachmentExtensions,
            ]
        ]) ?>
    </fieldset>

    <?= $form->errorSummary($model) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success', 'id'=>'save_button']) ?>
        <?php if(Yii::$app->user->hasAuthorizationFor('office-transactions')): ?>
            <?= Html::submitButton(Yii::t('app', 'Save and Notify'), ['class' => 'btn btn-success', 'style'=>'background-color: orange; border-color: orange', 'id'=>'save_and_notify_button', 'name'=>'TransactionForm[immediateNotification]', 'value'=>1]) ?>
        <?php endif ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
