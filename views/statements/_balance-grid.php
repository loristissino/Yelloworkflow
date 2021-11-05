<?php
use yii\helpers\Html;
use yii\grid\GridView;
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'summary' => '',
    'showFooter' => false,
    //'footerRowOptions' => ['class'=>'grid_footer'],
    'columns' => [
        [
            'label'=> Yii::t('app', 'Account Name'),
            'format'=>'raw',
            'value'=>'account_name',
        ],
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
    ],
]); ?>
