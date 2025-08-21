<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\ViewHelper;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $model app\models\Questionnaire */

$transitions = $model->getAuthorizedTransitions();
$currentStatus = $model->getWorkflowStatus()->getId();

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Questionnaires'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="questionnaire-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($currentStatus=='QuestionnaireWorkflow/draft'): ?>
    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php endif ?>
    
    <p>
    <?= $this->render('/workflow/_workflowbuttons', [
        'model' => $model,
        'transitions' => $transitions
    ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'description:ntext',
            'version',
            'wf_status',
            [
                'label' => Yii::t('app', 'Preview'),
                'format' => 'raw',
                'value' => function($model) {
                    return Html::a($model->id, ['questionnaire-responses/preview', 'id'=>$model->id], ['target'=>'_blank']);
                }
            ],
            [
                'attribute' => 'definition', // Or 'answers_data' for QuestionnaireResponse model
                'format' => 'raw', // Crucial: tells DetailView not to escape the HTML
                'value' => function ($model) {
                    // Decode the JSON string from the database (since it's a TEXT column)
                    $decodedDefinition = json_decode($model->definition, true); // Keep your JSON_decode call here

                    // Check for JSON decoding errors
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        return Html::tag('pre', Html::tag('code', Html::encode($model->definition))) .
                               Html::tag('div', 
                                   Yii::t('app', 'JSON Decoding Error: {error_msg}', ['error_msg' => json_last_error_msg()]), 
                                   ['class' => 'alert alert-danger']
                               );
                    }

                    // Use your custom helper to format and colorize the JSON
                    return Html::tag('div', ViewHelper::asJsonPrettyColorized($decodedDefinition), ['class' => 'json-display-container']);
                },
                'label' => Yii::t('app', 'Definition (JSON)'), // Custom label for clarity
            ],            
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
