<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Account */

$debitsTotalAmount = 0;
$creditsTotalAmount = 0;
foreach($postingDataProvider->models as $posting) {
    if ($posting->amount > 0) {
        $debitsTotalAmount += $posting->amount;
    }
    else {
        $creditsTotalAmount -= $posting->amount;
    }
}

$balance = $debitsTotalAmount - $creditsTotalAmount;
$balanceDescription = $balance >=0 ?
    sprintf('%s − %s', $model->debits_header, $model->credits_header)
    : 
    sprintf('%s − %s', $model->credits_header, $model->debits_header)
    ;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Statements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="account-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $postingDataProvider,
        'showFooter' => true,
        'summary' => Yii::t('app', 'Number of postings found: {count}.' ) . 
            '<br/>' . 
            Yii::t('app', 'Balance: {amount} ({description}).', [
                'amount'=>Yii::$app->formatter->asCurrency(abs($balance)),
                'description'=>$balanceDescription,
            ]),
        'footerRowOptions' => ['class'=>'grid_footer'],
        'columns' => [
            [
                'label' => Yii::t('app', 'Date'),
                'format' => 'raw',
                'attribute' => 'transaction.date',
                'value'=>function($data) {
                    return Yii::$app->formatter->asDate($data['transaction']['date']);
                },
            ],
            [
                'attribute' => Yii::t('app', 'Description'),
                'format' => 'raw',
                'value' => 'transaction.viewLink',
            ],
            [
                'attribute' => Yii::t('app', 'Report'),
                'format' => 'raw',
                'value' => 'transaction.periodicalReport.viewLink',
            ],
            [
                'attribute' => Yii::t('app', 'Notes'),
                'format' => 'raw',
                'value' => 'transaction.notes',
            ],
            [
                'attribute' => Yii::t('app', 'Status'),
                'format' => 'raw',
                'value' => 'transaction.workflowLabel',
            ],
            [
                'attribute' => $model->debits_header,
                'format' => 'raw',
                'value' => 'formattedDebitAmount',
                'footer' => Yii::$app->formatter->asCurrency($debitsTotalAmount),
                'contentOptions' => ['class' => 'amount'],
                'headerOptions' => ['class' => 'amount'],
                'footerOptions' => ['class' => 'amount'],
            ],
            [
                'attribute' => $model->credits_header,
                'format' => 'raw',
                'value' => 'formattedCreditAmount',
                'footer' => Yii::$app->formatter->asCurrency($creditsTotalAmount),
                'contentOptions' => ['class' => 'amount'],
                'headerOptions' => ['class' => 'amount'],
                'footerOptions' => ['class' => 'amount'],
            ],
        ],
    ]); ?>

</div>
