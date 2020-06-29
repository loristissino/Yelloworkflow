<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TransactionTemplate */

$this->title = Yii::t('app', 'Update Transaction Template: {name}', [
    'name' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Back End'), 'url'=>['backend/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transaction Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="transaction-template-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
