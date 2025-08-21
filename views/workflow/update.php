<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PeriodicalReportComment */

$this->title = Yii::t('app', 'Extraordinary workflow update for <em>{model}</em> {id}', [
    'model' => $model->model, 'id'=>$model->id
]);

$this->params['breadcrumbs'][] = ['label'=> Yii::t('app', 'Activities'), 'url'=>['activities/index']];
$this->params['breadcrumbs'][] = ['label'=> sprintf('%s %d', $model->model, $model->id), 'url'=>$return];
$this->params['breadcrumbs'][] = Yii::t('app', 'Workflow');
?>
<div class="workflow-update">

    <h1><?= $this->title ?></h1>
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
