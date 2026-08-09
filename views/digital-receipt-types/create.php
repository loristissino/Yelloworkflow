<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DigitalReceiptType */

$this->title = Yii::t('app', 'Create Digital Receipt Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Digital Receipt Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="digital-receipt-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
