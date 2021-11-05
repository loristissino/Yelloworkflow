<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'Balance');

if (Yii::$app->user->hasAuthorizationFor('transactions-management')) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Periodical Reports'), 'url' => ['periodical-reports-management/index']];
    $this->params['breadcrumbs'][] = ['label' => $ou->name];
    $this->params['breadcrumbs'][] = ['label' => $periodicalReport->name, 'url' => ['periodical-reports-management/view', 'id'=>$periodicalReport->id]];
}
else {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Periodical Reports'), 'url' => ['periodical-report-submissions/index']];
    $this->params['breadcrumbs'][] = ['label' => $periodicalReport->name, 'url' => ['periodical-report-submissions/view', 'id'=>$periodicalReport->id]];
}

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="balance-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= $ou ?>. <?= Yii::t('app', 'Balance at {date}.', ['date'=>Yii::$app->formatter->asDate($periodicalReport->end_date, 'php:d/m/Y')]) ?><br />
        <?= Yii::t('app', 'Only recorded transactions are taken into consideration.') ?>
    </p>
    
    <?= $this->render('/statements/_balance-grid', [
        'dataProvider' => $dataProvider,
    ]); ?> 

</div>
