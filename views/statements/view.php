<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\ViewHelper;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Account */

$debitsTotalAmount = 0;
$creditsTotalAmount = 0;

if ($historicalBalance) {
    if ($historicalBalance > 0) {
        $debitsTotalAmount = $historicalBalance;
    }
    if ($historicalBalance < 0) {
        $creditsTotalAmount = -$historicalBalance;
    }
}

foreach($postingDataProvider->models as $posting) {
        
    if ($posting->amount > 0) {
        $debitsTotalAmount += $posting->amount;
    }
    else {
        $creditsTotalAmount -= $posting->amount;
    }
}

$balance = $debitsTotalAmount - $creditsTotalAmount;
$balanceDescription = $model->getBalanceDescription($balance);

$historicalBalanceDescription = $model->getBalanceDescription($historicalBalance);

$inconsistency = '';
if (($balance >0 and $model->enforced_balance == 'C') or ($balance <0 and $model->enforced_balance == 'D')) {
    $inconsistency = ' 🔔 ' . Yii::t('app', 'This balance is inconsistent with the account definition.');
}

$hashLinkParams = ['view', 'id'=>$model->id]; 

$this->title = $model->name;
if ($year) {
    $this->title = sprintf('%s (%s)', $model->name, $year);
    $hashLinkParams['year']=$year;
}
if ($hash) {
    $this->title .= ' #' . $hash;
}

$hashLink = Url::toRoute($hashLinkParams);

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
            '<br/>' . ( 
            $historicalBalance ? 
                Yii::t('app', 'Initial balance: {amount} ({description}).', [
                    'amount'=>Yii::$app->formatter->asCurrency(abs($historicalBalance)),
                    'description'=>$historicalBalanceDescription,
                ]) . '<br>' 
                :
                '' ) . 
            '<strong>' . Yii::t('app', 'Final balance: {amount} ({description}).', [
                'amount'=>Yii::$app->formatter->asCurrency(abs($balance)),
                'description'=>$balanceDescription,
            ]) . '</strong>' .
            $inconsistency
            ,
        'footerRowOptions' => ['class'=>'grid_footer'],
        'columns' => [
            'transaction.id',
            [
                'label' => Yii::t('app', 'Date'),
                'format' => 'raw',
                'attribute' => 'date',
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
                'value' => function($data) use($hashLink) {
                    return ViewHelper::convertHashtagsToLinks($data['transaction']['notes'], $hashLink);
                },
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

<?php if ($year and $year > 2021): $linkedYear = $year-1 ?>
    <hr>
    <?= Html::a(Yii::t('app', 'Previous year ({year})', ['year'=>$linkedYear]), ['view', 'id'=>$model->id, 'year'=>$linkedYear]) ?>
<?php endif ?>
