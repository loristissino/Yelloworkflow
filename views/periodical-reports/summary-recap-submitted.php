<?php

use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PeriodicalReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Submitted Periodical Reports');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Periodical Reports'), 'url'=>['periodical-reports-management/index']];
$this->params['breadcrumbs'][] = $this->title;

$columns = [
    [
        'label'=>Yii::t('app', 'Periodical Report'),
        'format' => 'raw',
        'value'=>function($data) {
            return
                Html::a($data['name'], ['periodical-reports-management/view', 'id'=>$data['id']])
                . '<br />'
                . $data['ou']
            ;
        }
    ],
];

foreach ($fields as $field) {
    $columns[] = [
        'format' => 'raw',
        'label' => Yii::t(Yii::t('app', 'app\models\Transaction'), ucwords($field)),
        'value' => function($data) use($field) {
            if ($data[$field] == 0) {
                return '';
            }
            else {
                return $data[$field];
            }
        },
        'headerOptions' => ['style' => 'white-space: normal; text-align: right; width: 150px'],
        'contentOptions' => ['style' => 'text-align: right; width: 150px'],
    ];
}

?>
<div class="recap-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p><?= Yii::t('app', 'For each periodical report, the table shows the number of transactions in each possible workflow status.') ?></p>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $columns,
        'summary' => '',
        ]); 
    ?>

</div>
