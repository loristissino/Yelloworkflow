<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DigitalReceipt */

$this->title = Yii::t('app', 'Update «{name}»', ['name'=>$model->title]);

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Digital Receipts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="digital-receipt-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'digitalReceiptType'=>$digitalReceiptType,
        'organizationalUnit'=>$organizationalUnit,
    ]) ?>

</div>
