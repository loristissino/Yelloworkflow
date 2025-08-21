<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\QuestionnaireResponse */

$this->title = Yii::t('app', 'Create Questionnaire Response');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Questionnaire Responses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="questionnaire-response-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
