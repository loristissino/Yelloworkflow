<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View; // Import View class for registerJs

/* @var $this yii\web\View */
/* @var $model app\models\Questionnaire */
/* @var $form yii\widgets\ActiveForm */

// Define the Google Apps Script Web App URL here
// IMPORTANT: Replace with your actual deployed Google Apps Script Web App URL
$gasWebAppUrl = Yii::$app->params['gas']['form_structure_exporter']['url'];
$gasWebAppUser = Yii::$app->params['gas']['form_structure_exporter']['user'];

// Define a temporary attribute for the Google Form ID input,
// as it's not a direct model attribute to be saved, but used for import.
$model->google_form_id = $model->google_form_id ?? ''; 

?>

<div class="questionnaire-form">

    <?php $form = ActiveForm::begin(['id' => 'questionnaire-main-form']); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'version')->textInput() ?>

    <hr>

    <h3><?= Yii::t('app', 'Import from Google Forms') ?></h3>
    <p>
        <?= Yii::t('app', 'Enter the Google Form ID below and click "Import" to fetch the questionnaire structure.') ?><br>
        <?= Yii::t('app', 'Ensure the Google Form is shared with the script owner ({owner}) or is publicly accessible.', ['owner'=>$gasWebAppUser]) ?>
    </p>

    <div class="form-group">
        <?= Html::label(Yii::t('app', 'Google Form ID'), 'google-form-id-input', ['class' => 'control-label']) ?>
        <?= Html::textInput('google_form_id_input', $model->google_form_id, [
            'class' => 'form-control',
            'id' => 'google-form-id-input',
            'placeholder' => 'e.g., 1FAIpQLSc_...',
        ]) ?>
        <div class="help-block"></div>
    </div>

    <div class="form-group">
        <?= Html::button(Yii::t('app', 'Import Form Structure'), ['class' => 'btn btn-info', 'id' => 'import-google-form-btn']) ?>
        <span id="import-status" class="ml-2"></span>
    </div>

    <hr>

    <?= $form->field($model, 'definition')->textarea(['rows' => 15]) ?>

    <?php if ($model->hasErrors()): ?>
        <div class="alert alert-danger">
            <p><?= Yii::t('app', 'Please fix the following errors:') ?></p>
            <ul>
                <?php foreach ($model->getErrors() as $attributeErrors): ?>
                    <?php foreach ($attributeErrors as $error): ?>
                        <li><?= Html::encode($error) ?></li>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = <<<JS
$(document).ready(function() {
    const gasWebAppUrl = '{$gasWebAppUrl}';
    const importBtn = $('#import-google-form-btn');
    const googleFormIdInput = $('#google-form-id-input');
    const definitionTextarea = $('#questionnaire-definition'); // ID of the definition textarea
    const importStatus = $('#import-status');

    importBtn.on('click', async function() { // Added 'async' keyword
        const formId = googleFormIdInput.val().trim();

        if (!formId) {
            importStatus.html('<span class="text-danger">Please enter a Google Form ID.</span>');
            return;
        }

        importBtn.prop('disabled', true).text('Importing...');
        importStatus.html('<span class="text-info">Fetching form structure...</span>');

        try {
            // Construct the URL with query parameters
            const url = new URL(gasWebAppUrl);
            url.searchParams.append('formId', formId);

            const response = await fetch(url); // Use fetch API

            if (!response.ok) { // Check for HTTP errors (e.g., 404, 500)
                throw new Error('HTTP error! Status: ' + response.status);
            }

            const data = await response.json(); // Parse response as JSON

            if (data && !data.error) {
                // Pretty print the JSON for better readability in the textarea
                definitionTextarea.val(JSON.stringify(data, null, 2));
                importStatus.html('<span class="text-success">Form structure imported successfully!</span>');
            } else {
                const errorMessage = data.error || 'Unknown error occurred.';
                const details = data.details ? ' Details: ' + data.details : '';
                importStatus.html('<span class="text-danger">Error: ' + errorMessage + details + '</span>');
            }
        } catch (error) {
            importStatus.html('<span class="text-danger">Error during import: ' + error.message + '</span>');
            console.error('Fetch Error:', error);
        } finally {
            importBtn.prop('disabled', false).text('Import Form Structure');
        }
    });
});
JS;
$this->registerJs($js, View::POS_END); // Register JS at the end of the body
?>
