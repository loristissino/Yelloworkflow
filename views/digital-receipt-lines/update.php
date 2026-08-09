<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DigitalReceiptLine */

$this->title = Yii::t('app', 'Update Digital Receipt Line: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Digital Receipt Lines'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="digital-receipt-line-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
