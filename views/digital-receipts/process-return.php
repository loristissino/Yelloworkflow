<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DigitalReceipt */

$this->title = Yii::t('app', 'Process Return') . ' ' . $model->receipt->title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Digital Receipts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->receipt->title, 'url' => ['view', 'id' => $model->receipt->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Process Return');
?>
<div class="digital-receipt-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_return_form', [
        'model' => $model,
    ]) ?>

</div>
