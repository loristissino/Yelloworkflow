<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Fast Transactions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->description, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Handling');

$this->title = Yii::t('app', 'Notes on handling of «{transaction}»', [
    'transaction' => $model->description,
]);

?>
<div class="transaction-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_handling_form', [
        'model' => $model,
    ]) ?>

</div>
