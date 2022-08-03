<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Vendors');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Back End'), 'url'=>['backend/index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs(
    "
        let rows = $('tr');
        
        let colors = ['#FFFFFF', '#D7F4FD'];
        let key = 0;
        
        let previous = '';
        for (let i = 1; i<rows.length; i++) {
            let row = $(rows[i]);
            let v = row[0].children[1].innerText + row[0].children[2].innerText;
            if (v != previous) {
                key = (key+1) % 2;
                previous = v;
            }
            row.css('background-color', colors[key]);
        }
    ",
    \yii\web\View::POS_READY,
    'color'
);


?>
<div class="vendors-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => null,
        'columns' => [
            'id',
            'vendor',
            'vat_number',

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{update}',
				'buttons'=>[
					'update' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::toRoute(['vendors/update', 'id'=>$model['id']]));
                    },
                ],
            ]
        ],
    ]); ?>

</div>
