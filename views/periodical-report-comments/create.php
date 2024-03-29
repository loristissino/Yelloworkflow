<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PeriodicalReportComment */

$this->title = Yii::t('app', 'Create Periodical Report Comment');
$this->params['breadcrumbs'][] = ['label' => $periodicalReport->name, 'url' => [$controller . '/view', 'id'=>$periodicalReport->id]];
$this->params['breadcrumbs'][] = $this->title;

if ($reply_to and !$model->comment) {
    $model->comment = '> ' . $reply_to->comment . "\n";
} 

?>
<div class="periodical-report-comment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'controller' => $controller,
        'periodicalReport' => $periodicalReport,
    ]) ?>

</div>
