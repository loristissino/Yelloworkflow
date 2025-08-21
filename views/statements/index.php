<?php

use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\AccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Statements');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="account-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <p><?= Yii::t('app', 'The balances are computeted taking into consideration all transactions (draft, submitted, etc.) with the only excption of rejected ones.') ?></p>

    <h2><?= Yii::t('app', 'Real Accounts') ?></h2>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProviderForRealAccounts,
        'summary' => '',
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'label'=> Yii::t('app', 'Debits Balance'),
                'format'=>'raw',
                'value'=>function($data) {
                    $prefix = '';
                    if ($data['enforced_balance'] == 'C' and $data['amount_sum']>0) {
                        $prefix = '<span style="cursor: help;" title="' . Yii::t('app', 'This balance is inconsistent with the account definition.') .'">🔔</span> ';
                    }
                    return $data['amount_sum'] > 0 ? ($prefix . Yii::$app->formatter->asCurrency($data['amount_sum'])) : '';
                },
                'contentOptions' => ['class' => 'amount'],
                'headerOptions' => ['class' => 'amount'],
            ],
            [
                'label'=> Yii::t('app', 'Credits Balance'),
                'format'=>'raw',
                'value'=>function($data) {
                    $prefix = '';
                    if ($data['enforced_balance'] == 'D' and $data['amount_sum']<0) {
                        $prefix = '<span style="cursor: help;" title="' . Yii::t('app', 'This balance is inconsistent with the account definition.') .'">🔔</span> ';
                    }
                    return $data['amount_sum'] < 0 ? ($prefix . Yii::$app->formatter->asCurrency(-$data['amount_sum'])) : '';
                },
                'contentOptions' => ['class' => 'amount'],
                'headerOptions' => ['class' => 'amount'],
            ],
            [
            'class' => 'yii\grid\ActionColumn',
                'template'=>'{view}',
                'buttons'=>[
                    'view' => function ($url, $data) use($year) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'id'=>$data['id'], 'year'=>$year], [
                            'title'=>Yii::t('app', 'View'),
                            ]);
                        },
                ]
            ]
        ],
    ]); ?>

    <h2><?= Yii::t('app', 'Temporary Accounts for the year {year}', ['year'=>$year]) ?></h2>

    <?= GridView::widget([
        'dataProvider' => $dataProviderForTemporaryAccounts,
        'summary' => '',
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'label'=> Yii::t('app', 'Debits Balance'),
                'format'=>'raw',
                'value'=>function($data) {
                    return $data['amount_sum'] > 0 ? Yii::$app->formatter->asCurrency($data['amount_sum']) : '';
                },
                'contentOptions' => ['class' => 'amount'],
                'headerOptions' => ['class' => 'amount'],
            ],
            [
                'label'=> Yii::t('app', 'Credits Balance'),
                'format'=>'raw',
                'value'=>function($data) {
                    return $data['amount_sum'] < 0 ? Yii::$app->formatter->asCurrency(-$data['amount_sum']) : '';
                },
                'contentOptions' => ['class' => 'amount'],
                'headerOptions' => ['class' => 'amount'],
            ],
            [
            'class' => 'yii\grid\ActionColumn',
                'template'=>'{view}',
                'buttons'=>[
                    'view' => function ($url, $data) use($year) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'id'=>$data['id'], 'year'=>$year], [
                            'title'=>Yii::t('app', 'View'),
                            ]);
                        },
                ]
            ]
        ],
    ]); ?>

</div>

<hr>

<?= Html::a(Yii::t('app', 'Periodical Reports'), ['periodical-report-submissions/index']) ?>
