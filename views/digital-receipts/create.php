<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DigitalReceipt */

$digitalReceiptType = Yii::$app->session->get('digitalReceiptType');
$this->title = $digitalReceiptType->label;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Digital Receipts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="digital-receipt-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <p><?= Html::encode($digitalReceiptType->description) ?></p>

    <?= $this->render('_form', [
        'model' => $model,
        'digitalReceiptType'=>$digitalReceiptType,
        'organizationalUnit'=>$organizationalUnit,
    ]) ?>

</div>
