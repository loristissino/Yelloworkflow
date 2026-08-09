<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DigitalReceiptLine */

$this->title = Yii::t('app', 'Create Digital Receipt Line');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Digital Receipt Lines'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="digital-receipt-line-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
