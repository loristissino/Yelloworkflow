<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PeriodicalReportComment */

$this->title = Yii::t('app', 'Update Periodical Report Comment: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => 'Periodical Reports', 'url' => [$controller . '/index']];
$this->params['breadcrumbs'][] = ['label' => $periodicalReport->name, 'url' => [$controller . '/view', 'id'=>$periodicalReport->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="periodical-report-comment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
