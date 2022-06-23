<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PeriodicalReportComment */

$this->title = Yii::t('app', 'Extraordinary workflow update for {model} / {id}', [
    'model' => $model->model, 'id'=>$model->id
]);
$this->params['breadcrumbs'][] = ['label'=> Yii::t('app', 'Items'), 'url'=>$return];
$this->params['breadcrumbs'][] = Yii::t('app', 'Workflow');
?>
<div class="workflow-update">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
