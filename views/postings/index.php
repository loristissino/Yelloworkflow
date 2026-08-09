<?php

use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PostingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = Yii::t('app', 'Postings');
?>
<div class="posting-index">
    
    <h2><?= Html::encode($title) ?></h2>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => Yii::t('app', 'Account'),
                'format' => 'raw',
                'value' => function($data) {
                        return Yii\helpers\Html::a($data['account'], ['statements/view', 'id'=>$data['account']['id'], 'year'=>substr($data['transaction']['date'], 0, 4)]); 
                }
            ],
            [
                'attribute' => Yii::t('app', 'Description'),
                'format' => 'raw',
                'value' => 'description',
            ],
            /*
            [
                'attribute' => Yii::t('app', 'Amount'),
                'format' => 'raw',
                'value' => 'formattedSignedAmount',
                'contentOptions' => ['class' => 'amount'],
                'headerOptions' => ['class' => 'amount'],
                'footerOptions' => ['class' => 'amount'],
            ],
            */
            [
                'attribute' => Yii::t('app', 'Debit'),
                'format' => 'raw',
                'value' => 'formattedDebitAmount',
                'contentOptions' => ['class' => 'amount'],
                'headerOptions' => ['class' => 'amount'],
                'footerOptions' => ['class' => 'amount'],
            ],
            [
                'attribute' => Yii::t('app', 'Credit'),
                'format' => 'raw',
                'value' => 'formattedCreditAmount',
                'contentOptions' => ['class' => 'amount'],
                'headerOptions' => ['class' => 'amount'],
                'footerOptions' => ['class' => 'amount'],
            ],
            /*
            [
                'attribute' => Yii::t('app', 'Real Account'),
                'format' => 'raw',
                'value' => 'formattedSignedDebitAmount',
                'contentOptions' => ['class' => 'amount'],
                'headerOptions' => ['class' => 'amount'],
                'footerOptions' => ['class' => 'amount'],
            ],
            [
                'attribute' => Yii::t('app', 'Revenue'),
                'format' => 'raw',
                'value' => 'formattedSignedCreditAmount',
                'contentOptions' => ['class' => 'amount'],
                'headerOptions' => ['class' => 'amount'],
                'footerOptions' => ['class' => 'amount'],
            ],
            */
        ],
    ]); ?>

<?php if(Yii::$app->user->hasAuthorizationFor('transactions-management')): ?>
    <p><?= Html::a(Yii::t('app', 'Patch'), ['transactions-management/patch', 'id'=>$transaction->id]) ?></p>
<?php endif ?>
</div>
