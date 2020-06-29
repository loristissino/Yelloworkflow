<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TransactionTemplatePosting */

$template = $model->transactionTemplate;

$this->title = Yii::t('app', 'Posting with rank {rank}', ['rank'=>$model->rank]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Back End'), 'url'=>['backend/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transaction Templates'), 'url' => ['transaction-templates/index']];
$this->params['breadcrumbs'][] = ['label' => $template->title, 'url' => ['transaction-templates/view', 'id'=>$template->id]];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url'=> ['transaction-template-postings/view', 'id'=>$model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="transaction-template-posting-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
