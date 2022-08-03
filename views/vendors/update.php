<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */

$this->title = Yii::t('app', 'Update Vendor: {name}', [
    'name' => $model->vendor,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Back End'), 'url'=>['backend/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vendors'), 'url' => ['vendors/index']];
$this->params['breadcrumbs'][] = $model->vendor;
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="vendor-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
