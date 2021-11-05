<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Account */

$debitsTotalAmount = 0;
$creditsTotalAmount = 0;
$amountToBeReimbursed = 0;

foreach($postingDataProvider->models as $posting) {
    if ($posting->amount > 0) {
        $debitsTotalAmount += $posting->amount;
    }
    else {
        $creditsTotalAmount -= $posting->amount;
    }
    
    if ($posting->transaction->getWorkflowStatus()->getId() == 'TransactionWorkflow/recorded') {
        $amountToBeReimbursed += $posting->amount;
    }
}

$balance = $debitsTotalAmount - $creditsTotalAmount;

\yii\web\YiiAsset::register($this);
?>
<?php if (count($postingDataProvider->models)>0): ?>

<div class="account-view">

    <h2><?= Yii::t('app', 'Paid Expenses') ?></h2>

    <?= GridView::widget([
        'dataProvider' => $postingDataProvider,
        'showFooter' => true,
        'summary' => Yii::t('app', 'Number of postings found: {count}.' ) . 
            '<br/>' . 
            Yii::t('app', 'Amount to be reimbursed: {amount} (only expenses recorded and not reimbursed are summarized here).', [
                'amount'=>Yii::$app->formatter->asCurrency($amountToBeReimbursed)
            ]),
        'footerRowOptions' => ['class'=>'grid_footer'],
        'columns' => [
            [
                'label' => Yii::t('app', 'Date'),
                'format' => 'raw',
                'value'=>function($data) {
                    return Yii::$app->formatter->asDate($data['transaction']['date']);
                },
            ],
            [
                'label' => Yii::t('app', 'Description'),
                'format' => 'raw',
                'value' => 'transaction.viewLink',
            ],
            [
                'label' => Yii::t('app', 'Periodical Report'),
                'format' => 'raw',
                'value' => 'transaction.periodicalReport.viewLink',
            ],
            [
                'label' => Yii::t('app', 'Notes'),
                'format' => 'raw',
                'value' => 'transaction.notes',
            ],
            [
                'label' => Yii::t('app', 'Status'),
                'format' => 'raw',
                'value' => 'transaction.workflowLabel',
            ],
            [
                'label' => Yii::t('app', 'Expense'),
                'format' => 'raw',
                'value' => 'formattedDebitAmount',
                'footer' => Yii::$app->formatter->asCurrency($debitsTotalAmount),
                'contentOptions' => ['class' => 'amount'],
                'headerOptions' => ['class' => 'amount'],
                'footerOptions' => ['class' => 'amount'],
            ],
            [
                'label' => Yii::t('app', 'Donation'),
                'format' => 'raw',
                'value' => 'formattedCreditAmount',
                'footer' => Yii::$app->formatter->asCurrency($creditsTotalAmount),
                'contentOptions' => ['class' => 'amount'],
                'headerOptions' => ['class' => 'amount'],
                'footerOptions' => ['class' => 'amount'],
            ],
        ],
    ]); ?>

    <?php if ($amountToBeReimbursed > 0 and Yii::$app->user->hasAuthorizationFor('office-transactions/create')): ?>
        <?=Html::a(Yii::t('app', 'Create Reimbursement Transaction'), ['office-transactions/create', 'organizational_unit_id'=>$project->organizational_unit_id, 'project_id'=>$project->id, 'amount'=>$amountToBeReimbursed]) ?>
    <?php endif ?>


</div>
<?php endif ?>
