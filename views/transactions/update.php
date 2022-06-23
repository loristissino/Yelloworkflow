<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */

$office_transaction = false;

if (Yii::$app->controller->id == 'office-transactions') {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Office Transactions'), 'url'=>['office-transactions/index']];
    $office_transaction = true;
}
else {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Periodical Reports'), 'url' => ['periodical-report-submissions/index']];
    $this->params['breadcrumbs'][] = ['label' => $model->periodicalReport->name, 'url' => ['periodical-report-submissions/view', 'id'=>$model->periodicalReport->id]];
    $this->params['breadcrumbs'][] = ['label' => $model->description, 'url' => ['view', 'id' => $model->id]];
    $this->params['breadcrumbs'][] = Yii::t('app', 'Update');
}

$this->title = Yii::t('app', 'Update Transaction: {name}', [
    'name' => $model->description,
]);

?>
<div class="transaction-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_transaction_form', [
        'model' => $model,
        'office_transaction'=>$office_transaction,
    ]) ?>

</div>
