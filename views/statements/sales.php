<?php

use yii\helpers\Html;
use yii\grid\GridView;

switch($view) {
    case 'lines':
        $totalAmount = 0;
        foreach($dataProvider->models as $row) {
            $totalAmount += $row['amount'];
        }
        
        $columns = [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label'=>Yii::t('app', 'Date'),
                'attribute'=>'date',
                'format'=>'raw',
                'value'=>function($data) {
                    return Yii::$app->formatter->asDate($data['date']);
                },
            ],
            [
                'label'=>Yii::t('app', 'Name'),
                'attribute'=>'name',
                'format'=>'raw',
            ],
            [
                'label' => Yii::t('app', 'Amount'),
                'attribute' => 'amount',
                'format' => 'raw',
                'value' => function ($row, $index) {
                    return  Yii::$app->formatter->asCurrency($row['amount']);
                },
                'footer' => Yii::$app->formatter->asCurrency($totalAmount),
                'contentOptions' => ['class' => 'amount'],
                'headerOptions' => ['class' => 'amount'],
                'footerOptions' => ['class' => 'amount'],
            ],
        ];
        break;

    case 'pivot':
        //echo "<pre>";
        $dateField = Yii::t('app', 'Date');
        $columns = [
            $dateField,
        ];

        $data = [];

        $totals = [];

        foreach($dataProvider->models as $row) {
            $data[] = [
                $dateField => $row['date'],
                //$row['name']=> Yii::$app->formatter->asCurrency($row['amount']),
                $row['name']=> $row['amount'], //is_numeric($row['amount']) ? floatval($row['amount']) : 0,
            ];
            if (!array_key_exists($row['name'], $totals)){
                $totals[$row['name']] = 0;
            }
            $totals[$row['name']]+=$row['amount'];
        }

        
        foreach($dataProvider->models as $row) {
            if(!in_array($row['name'], $columns)) {
                $columns[$row['name']] = [
                    'attribute' => $row['name'],
                    'label' => $row['name'],
                    'format' => 'raw',
                    'contentOptions' => ['class' => 'amount'],
                    'headerOptions' => ['class' => 'amount'],
                    'footerOptions' => ['class' => 'amount'],
                    'footer' => Yii::$app->formatter->asCurrency($totals[$row['name']]),
                ];
            }
        }

        for($i=0; $i<sizeof($data); $i++) {
            foreach(array_diff_key($columns, [0]) as $column=>$value) {
                if (!array_key_exists($column, $data[$i])) {
                    $data[$i][$column] = '';
                }
            }
        }
        
        $newdata = [];
        foreach($data as $value) {
            if ( ! array_key_exists($value[$dateField], $newdata)) {
                $newdata[$value[$dateField]]=$value;
                foreach(array_keys($totals) as $field) {
                    if (!$newdata[$value[$dateField]][$field]) {
                        $newdata[$value[$dateField]][$field] = 0;
                    }
                }
            }
            else {
                foreach(array_keys($totals) as $field) {
                    $newdata[$value[$dateField]][$field] += ($value[$field] ? $value[$field] : 0);
                }
            }
        }
        
        $data = [];
        foreach($newdata as $value) {
            foreach(array_keys($totals) as $field) {
                $value[$field] = $value[$field] == 0 ? '' : Yii::$app->formatter->asCurrency($value[$field]);
            }
            $data[] = $value;
        }
        
       /*
        print_r($data);
        print_r($newdata);
        print_r($totals);
        
        
        print_r($columns);
        
        die();
        */
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $data,
            'sort' => [
            'attributes' => ['id', 'name'],
            ],
            'pagination' => [
                'pageSize' => 1000,
            ],
        ]);

        break;
    
    default:
        throw new \yii\web\NotFoundHttpException("The requested view does not exist.");
}


$this->title = Yii::t('app', 'Sales');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Periodical Reports'), 'url' => ['periodical-report-submissions/index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['periodical-report-submissions/view', 'id'=>$model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sales-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '',
        'showFooter' => true,
        'footerRowOptions' => ['class'=>'grid_footer'],
        'columns' => $columns,
    ]); ?>

</div>
