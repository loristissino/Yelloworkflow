<?php

use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PeriodicalReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Balances');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Periodical Reports'), 'url'=>['periodical-reports-management/index']];
$this->params['breadcrumbs'][] = $this->title;

$columns = [
    [
        'attribute'=>Yii::t('app', 'Organizational Unit'),
        'format' => 'raw',
        'value'=>function($data) {
            if ($data['status']==0){
                return '<span style="text-decoration: line-through;">' .$data['ou'] . '</span>';
            }
            return $data['ou'];
        }
        
    ],
    [
        'label'=>Yii::t('app', 'Ceiling Amount'),
        'format' => 'raw',
        'value'=>
        function($data) {
            $difference = -$data[Yii::$app->params['periodicalReports']['accountToCompareToCeilingAmount']] - $data['ceiling_amount'];
            $prefix = ($difference > 0) ? '<span style="cursor: help;" title="' . Yii::t('app', 'The Ceiling Amount has been exceed of {amount}.', ['amount'=>Yii::$app->formatter->asCurrency($difference)]) .'">🔔</span> ': '';
            return $prefix . Yii::$app->formatter->asCurrency($data['ceiling_amount']);
        }
        ,
        'contentOptions' => ['class' => 'amount', 'style'=>'color: #0000FF'],
        'headerOptions' => ['style' => 'white-space: normal'],
        'footerOptions' => ['class' => 'amount'],    ],
];

foreach ($fields as $field) {
    $columns[] = [
        'attribute' => $field,
        'format' => 'raw',
        'label' => $field,
        'value' => function($data) use ($field, $enforcedBalances) {
            if ($data[$field] == 0) {
                return '';
            }
            else {
                $fv = Yii::$app->formatter->asCurrency($data[$field]);
                
                $prefix = '';
                if (($enforcedBalances[$field]=='C' and $data[$field]>0) or ($enforcedBalances[$field]=='D' and $data[$field]<0)) {
                    $prefix = '<span style="cursor: help;" title="' . Yii::t('app', 'This balance is inconsistent with the account definition.') .'">🔔</span> ';
                }
                
                return $prefix . ($data[$field]>0? $fv: '<span style="color: red">'.$fv.'</span>');
            }
        },
        'contentOptions' => ['class' => 'amount'],
        'headerOptions' => ['style' => 'white-space: normal'],
        'footerOptions' => ['class' => 'amount'],
    ];
    
}

?>
<div class="balances-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <p><?= Yii::t('app', 'Only recorded transactions are taken into consideration.') ?> <?= Yii::t('app', 'Only organizational units which have their own cash are shown.') ?></p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $columns,
        'summary' => '',
        ]); 
    ?>

</div>
