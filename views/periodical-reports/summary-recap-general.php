<?php

use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PeriodicalReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Recap');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Periodical Reports'), 'url'=>['periodical-reports-management/index']];
$this->params['breadcrumbs'][] = $this->title;

$columns = [
    [
        'label'=>Yii::t('app', 'Periodical Report'),
        'format' => 'raw',
        'value'=>function($data) {
            return Html::a($data['name'], ['periodical-reports-management/index', 'PeriodicalReportSearch[name]'=>$data['name']]);
        }
    ],
];

foreach ($fields as $field) {
    $columns[] = [
        'format' => 'raw',
        'label' => Yii::t(Yii::t('app', 'app\models\PeriodicalReport'), ucwords($field)),
        'value' => function($data) use($field) {
            if ($data[$field] == 0) {
                return '';
            }
            else {
                return Html::a($data[$field], ['periodical-reports-management/index', 'PeriodicalReportSearch[name]'=>$data['name'], 'PeriodicalReportSearch[wf_status]'=>$field]);
            }
        },
        'headerOptions' => ['style' => 'white-space: normal; text-align: right; width: 150px'],
        'contentOptions' => ['style' => 'text-align: right; width: 150px'],
    ];
}

?>
<div class="recap-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $columns,
        'summary' => '',
        ]); 
    ?>

</div>
