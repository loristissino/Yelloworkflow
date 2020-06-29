<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TransactionTemplatePosting */

$template = $model->transactionTemplate;
$account = $model->account;

$this->title = Yii::t('app', 'Posting with rank {rank}', ['rank'=>$model->rank]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Back End'), 'url'=>['backend/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transaction Templates'), 'url' => ['transaction-templates/index']];
$this->params['breadcrumbs'][] = ['label' => $template->title, 'url' => ['transaction-templates/view', 'id'=>$template->id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="transaction-template-posting-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'label'=>Yii::t('app', 'Transaction Template'),
                'format'=>'raw',
                'value'=>Html::a($template->title, ['transaction-templates/view', 'id'=>$template->id]),
            ],
            'rank',
            [
                'label'=>Yii::t('app', 'Account'),
                'format'=>'raw',
                'value'=>Html::a($account->name, ['accounts/view', 'id'=>$account->id]),
            ],
            [
                'label'=>Yii::t('app', 'Debit / Credit'),
                'format'=>'raw',
                'value'=>$model->viewDC,
            ],
            [
                'label'=>Yii::t('app', 'Amount'),
                'format'=>'raw',
                'value'=>$model->formattedAmount,
            ],
        ],
    ]) ?>

</div>
