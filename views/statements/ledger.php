<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Account */

$sumOfDebits = 0;
$sumOfCredits = 0;
foreach($postingDataProvider->models as $posting) {
    if ($posting->amount > 0) {
        $sumOfDebits += $posting->amount;
    }
    else {
        $sumOfCredits -= $posting->amount;
    }
}

$balance = $sumOfDebits - $sumOfCredits;

// for some accounts the condition is inverted here (because everything is seen from the opposite point of view)

$this->title = sprintf('%s (%s)', $model->name, $ou->name);

if ($model->shown_in_ou_view == 2) {
    $this->title = sprintf('%s (%s)', $model->reversed_name, $ou->name);
    $balanceDescription = $balance < 0 ?
        sprintf('%s − %s', $model->debits_header, $model->credits_header)
        : 
        sprintf('%s − %s', $model->credits_header, $model->debits_header)
        ;
    $columnsShown = [ 'Credit', 'Debit' ];

}
else {
    $balanceDescription = $balance < 0 ?
        sprintf('%s − %s', $model->credits_header, $model->debits_header)
        : 
        sprintf('%s − %s', $model->debits_header, $model->credits_header)
        ;
    $columnsShown = [ 'Debit', 'Credit' ];
}

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Dashboard'), 'url'=>['site/dashboard']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="account-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $postingDataProvider,
        'showFooter' => true,
        'summary' => Yii::t('app', 'Number of postings found: {count}.' ) . 
            '<br>' . 
            Yii::t('app', 'Balance: {amount} ({description}).', [
                'amount'=>Yii::$app->formatter->asCurrency(abs($balance)),
                'description'=>$balanceDescription,
            ]) .
            '<br>' .
            '💡 ' . Yii::t('app', 'Only the transactions in the selected statuses are taken into consideration.') .
            ' ' . Html::a(Yii::t('app', 'Check/Edit Preferences'), ['periodical-reports-management/summary', 'type'=>'balances', '#'=>'statuses'], ['target'=>'_blank'])
            ,
        'footerRowOptions' => ['class'=>'grid_footer'],
        'columns' => [
            [
                'attribute' => 'Date',
                'format' => 'raw',
                'value' => 'transaction.date',
            ],
            [
                'attribute' => 'Description',
                'format' => 'raw',
                'value' => 'transaction.viewLink',
            ],
            [
                'attribute' => 'Periodical Report',
                'format' => 'raw',
                'value' => 'transaction.periodicalReport.viewLink',
            ],
            [
                'attribute' => 'Notes',
                'format' => 'raw',
                'value' => 'transaction.notes',
            ],
            [
                'attribute' => 'Status',
                'format' => 'raw',
                'value' => 'transaction.workflowLabel',
            ],
            // Please note: the ledger here is from the inverted point of view, so the two columns are reversed
            // but the names are still the same
            [
                'attribute' => $model->debits_header,
                'format' => 'raw',
                'value' => sprintf('formatted%sAmount', $columnsShown[0]),
                'footer' => Yii::$app->formatter->asCurrency($columnsShown[0] == 'Debit' ? $sumOfDebits : $sumOfCredits),
                'contentOptions' => ['class' => 'amount'],
                'headerOptions' => ['class' => 'amount'],
                'footerOptions' => ['class' => 'amount'],
            ],
            [
                'attribute' => $model->credits_header,
                'format' => 'raw',
                'value' => sprintf('formatted%sAmount', $columnsShown[1]),
                'footer' => Yii::$app->formatter->asCurrency($columnsShown[1] == 'Credit' ? $sumOfCredits : $sumOfDebits),
                'contentOptions' => ['class' => 'amount'],
                'headerOptions' => ['class' => 'amount'],
                'footerOptions' => ['class' => 'amount'],
            ],
        ],
    ]); ?>

</div>
