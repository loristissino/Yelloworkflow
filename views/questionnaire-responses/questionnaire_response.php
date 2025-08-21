<?php

use yii\helpers\Html;
use app\models\Questionnaire;
use app\models\QuestionnaireResponse; // Assuming your model is named QuestionnaireResponse

/* @var $this yii\web\View */
/* @var $questionnaireResponse QuestionnaireResponse */
/* @var $questionnaire Questionnaire */

$this->title = Yii::t('app', 'Response for «{questionnaire_title}»', [
    'questionnaire_title' => $questionnaire->title
]);
// Adjust the breadcrumbs to match your application's navigation structure
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Questionnaires and Responses'), 'url' => ['index']]; 
$this->params['breadcrumbs'][] = $this->title;

// The 'definition' and 'answers_data' attributes cannot be automatically
// decoded into PHP arrays/objects by Yii2's JSON column handling, because the DBMS 
// does not support it. Instead, we will decode the data using the PHP function.
// So, no need for json_decode() here.

$questionnaireDefinition = JSON_decode($questionnaire->definition, true);
$answersData = JSON_decode($questionnaireResponse->content, true);

$model = $questionnaireResponse; // shortcut

$transitions = $model->getAuthorizedTransitions();
$currentStatus = $model->getWorkflowStatus()->getId();

?>

<div class="questionnaire-response-view">
    <h1><?= Html::encode($questionnaire->title) ?></h1>
    <?php if (!empty($questionnaire->description)): ?>
        <p class="lead"><?= Html::encode($questionnaire->description) ?></p>
    <?php endif; ?>

    <p>
        
    <?= $model->isDraft ? Html::a(Yii::t('app', 'Update'), ['fill', 'id' => $model->questionnaire_id, 'response'=>$model->id], ['class' => 'btn btn-primary']) : '' ?>

    <?= $this->render('/workflow/_workflowbuttons', [
        'model' => $model,
        'transitions' => $transitions
    ]) ?>
    </p>


    <hr>

    <h2>
        <?= Yii::t('app', 'Your Answers') ?>
        <?php if($questionnaireResponse->label): ?>
            («<?= $questionnaireResponse->label ?>»)
        <?php endif ?>
    
    </h2>

    <?php
    // Check if the questionnaire definition has questions and is an array
    if (isset($questionnaireDefinition['questions']) && is_array($questionnaireDefinition['questions'])) {
        foreach ($questionnaireDefinition['questions'] as $question) {
            $questionId = $question['id'];
            $questionTitle = Html::encode($question['title']);
            
            // Retrieve the answer for the current question ID from the answers data
            // Use array_key_exists to safely check if the answer exists, then get it.
            // Default to null if not found.
            $answer = array_key_exists($questionId, $answersData) ? $answersData[$questionId] : null;

            echo Html::tag('h4', $questionTitle);

            // Display the answer based on question type
            switch ($question['type']) {
                case 'text':
                case 'paragraph_text':
                case 'list':
                    echo Html::tag('p', !empty($answer) ? nl2br(Html::encode($answer)) : Yii::t('app', 'No answer provided.'));
                    break;

                case 'multiple_choice':
                    // Check if an answer was provided
                    if (!empty($answer)) {
                        // For 'multiple_choice' with 'hasOtherOption', the answer might be the "other" text itself.
                        // We check if the answer is NOT one of the predefined options, implying it's the custom "other" input.
                        $options = $question['options'] ?? [];
                        if (isset($question['hasOtherOption']) && $question['hasOtherOption'] && !in_array($answer, $options)) {
                            echo Html::tag('p', Html::encode(Yii::t('app', 'Other: {answer}', ['answer' => $answer])));
                        } else {
                            // Regular multiple choice option
                            echo Html::tag('p', Html::encode($answer));
                        }
                    } else {
                        echo Html::tag('p', Yii::t('app', 'No answer provided.'));
                    }
                    break;

                case 'checkbox':
                    // If checkbox answers are stored as an array of selected options
                    if (is_array($answer) && !empty($answer)) {
                       echo Html::ul($answer, ['item' => function($item, $index) {
                           return Html::tag('li', Html::encode($item));
                       }]);
                    } else {
                       echo Html::tag('p', Yii::t('app', 'No answer provided.'));
                    }
                    break;

                // Add more cases for other question types if you implement them (e.g., 'number', 'email', 'date')
                // case 'number':
                //     echo Html::tag('p', !empty($answer) ? Html::encode($answer) : Yii::t('app', 'No answer provided.'));
                //     break;

                default:
                    // Fallback for unsupported types or types not explicitly handled
                    echo Html::tag('p', Yii::t('app', 'Unsupported question type or no answer: {type}', ['type' => Html::encode($question['type'])]));
                    break;
            }
            echo '<br>'; // Add some spacing between questions
        }
    } else {
        echo Html::tag('p', Yii::t('app', 'No questions defined for this questionnaire.'), ['class' => 'alert alert-info']);
    }
    ?>
    
</div>

<?php if (!$model->isDraft): ?>

<hr>
<p><?= Html::a(Yii::t('app', 'Clone this response to pre-fill something similar'), ['questionnaire-responses/fill', 'id'=>$model->questionnaire_id, 'response'=>$model->id, 'clone'=>1]) ?></p>

<?php endif ?>
