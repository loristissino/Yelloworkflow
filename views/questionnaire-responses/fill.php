<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Questionnaire;
use app\models\QuestionnaireForm;

/* @var $this yii\web\View */
/* @var $questionnaire Questionnaire */
/* @var $formModel QuestionnaireForm */

$this->title = $questionnaire->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Questionnaires and Responses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Retrieve the definition from the formModel
$questionnaireDefinition = $formModel->getQuestionnaireDefinition();

?>

<div class="questionnaire-fill">
    <h1><?= Html::encode($questionnaire->title) ?></h1>
    <?php if (!empty($questionnaire->description)): ?>
        <p class="lead"><?= Html::encode($questionnaire->description) ?></p>
    <?php endif; ?>

    <?php $form = ActiveForm::begin(['id' => 'questionnaire-form']); ?>

    <?php
    if (isset($questionnaireDefinition['questions']) && is_array($questionnaireDefinition['questions'])) {
        foreach ($questionnaireDefinition['questions'] as $question) {
            $questionId = $question['id'];
            $attributeName = 'q_' . $questionId; // This must match the attribute name in QuestionnaireForm

            $questionTitle = Html::encode($question['title']);
            $helpText = !empty($question['helpText']) ? Html::encode($question['helpText']) : ''; // Ensure helpText is encoded
            $isRequired = isset($question['isRequired']) && $question['isRequired'];

            switch ($question['type']) {
                case 'text':
                    echo $form->field($formModel, $attributeName)->textInput([
                        'placeholder' => $helpText,
                        'required' => $isRequired,
                    ])->label($questionTitle);
                    if (!empty($helpText)) {
                        echo Html::tag('div', $helpText, ['class' => 'help-block']);
                    }
                    break;

                case 'paragraph_text':
                    echo $form->field($formModel, $attributeName)->textarea([
                        'rows' => 6,
                        'placeholder' => $helpText,
                        'required' => $isRequired,
                    ])->label($questionTitle);
                    if (!empty($helpText)) {
                        echo Html::tag('div', $helpText, ['class' => 'help-block']);
                    }
                    break;

                case 'multiple_choice':
                    $options = $question['options'] ?? [];
                    $items = [];
                    foreach ($options as $option) {
                        $items[$option] = Html::encode($option);
                    }

                    // Handle 'Other' option
                    $otherAttributeName = null;
                    if (isset($question['hasOtherOption']) && $question['hasOtherOption']) {
                        $items['__other__'] = Yii::t('app', 'Other:'); // Just the label for 'Other'
                        $otherAttributeName = $attributeName . '_other_text'; // The attribute for the text input
                    }

                    echo $form->field($formModel, $attributeName)->radioList($items, [
                        'required' => $isRequired,
                        'item' => function ($index, $label, $name, $checked, $value) use ($question, $attributeName, $formModel, $otherAttributeName) {
                            $radioId = Html::getInputId($formModel, $attributeName) . '_' . $index;
                            $radio = Html::radio($name, $checked, ['value' => $value, 'id' => $radioId]);
                            $labelHtml = Html::label($label, $radioId);

                            $itemContent = $radio . $labelHtml;

                            // If this is the "Other" option, add the text input conditionally
                            if ($value === '__other__' && $otherAttributeName !== null) {
                                // Add has-error class to the wrapper if there's an error for the other_text field
                                $hasErrorClass = $formModel->hasErrors($otherAttributeName) ? ' has-error' : '';
                                $errorHtml = $formModel->hasErrors($otherAttributeName) ? 
                                    Html::tag('div', Html::encode($formModel->getFirstError($otherAttributeName)), ['class' => 'help-block help-block-error']) : '';

                                $otherInput = Html::textInput(
                                    Html::getInputName($formModel, $otherAttributeName),
                                    $formModel->{$otherAttributeName} ?? null,
                                    [
                                        'class' => 'form-control',
                                        'placeholder' => Yii::t('app', 'Specify other'),
                                        'style' => $checked ? '' : 'display: none;', // Hide by default if not checked
                                        'id' => Html::getInputId($formModel, $otherAttributeName), // Give it an ID for JS
                                    ]
                                );
                                // Wrap the other input and its error in a div with appropriate classes
                                $itemContent .= Html::tag('div', $otherInput . $errorHtml, ['class' => 'other-text-input form-group' . $hasErrorClass]);
                            }

                            return Html::tag('div', $itemContent, ['class' => 'radio']);
                        },
                        'unselect' => null, // Prevents an empty hidden input when no radio is selected
                        'encode' => false, // Important for HTML labels (like for 'Other:')
                    ])->label($questionTitle);

                    if (!empty($helpText)) {
                        echo Html::tag('div', $helpText, ['class' => 'help-block']);
                    }
                    break;

                case 'checkbox':
                    $options = $question['options'] ?? [];
                    $items = [];
                    foreach ($options as $option) {
                        $items[$option] = Html::encode($option);
                    }

                    // Handle 'Other' option for checkboxes
                    $otherAttributeName = null;
                    if (isset($question['hasOtherOption']) && $question['hasOtherOption']) {
                        $items['__other__'] = 'Other:'; // Just the label for 'Other'
                        $otherAttributeName = $attributeName . '_other_text'; // The attribute for the text input
                    }

                    echo $form->field($formModel, $attributeName)->checkboxList($items, [
                        'required' => $isRequired, // This will be handled by the 'required' rule in model
                        'item' => function ($index, $label, $name, $checked, $value) use ($question, $attributeName, $formModel, $otherAttributeName) {
                            $checkboxId = Html::getInputId($formModel, $attributeName) . '_' . $index;
                            $checkbox = Html::checkbox($name, $checked, ['value' => $value, 'id' => $checkboxId]);
                            $labelHtml = Html::label($label, $checkboxId);

                            $itemContent = $checkbox . $labelHtml;

                            // If this is the "Other" option, add the text input conditionally
                            if ($value === '__other__' && $otherAttributeName !== null) {
                                // Add has-error class to the wrapper if there's an error for the other_text field
                                $hasErrorClass = $formModel->hasErrors($otherAttributeName) ? ' has-error' : '';
                                $errorHtml = $formModel->hasErrors($otherAttributeName) ? 
                                    Html::tag('div', Html::encode($formModel->getFirstError($otherAttributeName)), ['class' => 'help-block help-block-error']) : '';

                                $otherInput = Html::textInput(
                                    Html::getInputName($formModel, $otherAttributeName),
                                    $formModel->{$otherAttributeName} ?? null,
                                    [
                                        'class' => 'form-control',
                                        'placeholder' => 'Specify other',
                                        'style' => $checked ? '' : 'display: none;', // Hide by default if not checked
                                        'id' => Html::getInputId($formModel, $otherAttributeName), // Give it an ID for JS
                                    ]
                                );
                                // Wrap the other input and its error in a div with appropriate classes
                                $itemContent .= Html::tag('div', $otherInput . $errorHtml, ['class' => 'other-text-input form-group' . $hasErrorClass]);
                            }

                            return Html::tag('div', $itemContent, ['class' => 'checkbox']);
                        },
                        'encode' => false, // Important for HTML labels (like for 'Other:')
                    ])->label($questionTitle);

                    if (!empty($helpText)) {
                        echo Html::tag('div', $helpText, ['class' => 'help-block']);
                    }
                    break;

                case 'list': // Dropdown
                    $options = $question['options'] ?? [];
                    $items = [];
                    foreach ($options as $option) {
                        $items[$option] = Html::encode($option);
                    }
                    
                    // Add a default empty option for dropdowns if not required or for better UX
                    $prompt = $isRequired ? null : Yii::t('app', 'Select an option...');

                    echo $form->field($formModel, $attributeName)->dropDownList($items, [
                        'prompt' => $prompt,
                        'required' => $isRequired,
                    ])->label($questionTitle);
                    if (!empty($helpText)) {
                        echo Html::tag('div', $helpText, ['class' => 'help-block']);
                    }
                    break;


                default:
                    echo Html::tag('p', 'Unsupported question type: ' . Html::encode($question['type']), ['class' => 'alert alert-warning']);
                    break;
            }
        }
    } else {
        echo Html::tag('p', 'No questions defined for this questionnaire.', ['class' => 'alert alert-info']);
    }
    ?>
    
    <?php if($preview): ?>
        <?= Yii::t('app', 'This is a preview.') ?>
    <?php else: ?>
        <hr>
        <?= $form->field($formModel, 'label')->textInput(['maxlength' => true])->hint(Yii::t('app', 'You can assign a label to this response, in order to find it if you need.')) ?>
    
    
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save Answers'), ['class' => 'btn btn-success']) ?>
        </div>
    <?php endif ?>

    <?php ActiveForm::end(); ?>
    
    <?php /*
    <pre>
    <?php print_r($form->errorSummary($formModel)); // You can keep this for debugging, but typically remove in production ?>
    </pre>
    */ ?>

</div>
<?php
$js = <<<JS
$(document).on('change', '.radio input[type="radio"], .checkbox input[type="checkbox"]', function() {
    var inputElement = $(this);
    var inputType = inputElement.attr('type');
    var inputName = inputElement.attr('name');
    
    // Find the 'other' radio button's parent .radio div, then find its associated other-text-input div
    var otherOptionContainer;
    if (inputType === 'radio') {
        otherOptionContainer = $('input[name="' + inputName + '"][value="__other__"]').closest('.radio');
    } else { // For checkboxes, it's simpler as each is independent
        otherOptionContainer = inputElement.closest('.checkbox');
    }

    var otherTextInputWrapper = otherOptionContainer.find('.other-text-input');
    var otherTextInput = otherTextInputWrapper.find('input[type="text"]');

    if (inputElement.val() === '__other__') {
        if (inputElement.is(':checked')) {
            otherTextInput.show();
        } else { // For checkboxes, if __other__ is unchecked
            otherTextInput.hide();
            otherTextInput.val('');
        }
    } else {
        // If a non-__other__ radio is selected, hide any other text inputs in the same group
        if (inputType === 'radio') {
            $('input[name="' + inputName + '"][value="__other__"]').closest('.radio').find('.other-text-input input[type="text"]').hide().val('');
        }
        // For checkboxes, non-__other__ selections don't affect the __other__ text input
    }
});

// Initialize on page load for any pre-selected 'other' options
$('.radio input[type="radio"]:checked, .checkbox input[type="checkbox"]:checked').each(function() {
    if ($(this).val() === '__other__') {
        var otherTextInput = $(this).closest('.radio, .checkbox').find('.other-text-input input[type="text"]');
        otherTextInput.show();
    }
});
JS;
$this->registerJs($js);
?>
