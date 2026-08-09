<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Products');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <?php if(Yii::$app->user->hasAuthorizationFor('products/manage')): ?>
        <p><?= Html::a(Yii::t('app', 'Products Management'), ['products/manage'], ['class' => 'btn btn-info']) ?></p>
    <?php endif ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'digital_receipt_type_id',
            //'organizational_unit_id',
            'rank',
            'sku',
            //'status',
            'isbn',
            'author',
            [
                'attribute'=>'description',
                'format'=>'raw',
                'value'=>function($data) {
                    if ($data['url']) {
                        return Html::a($data['description'], $data->url, ['target'=>'_blank']);
                    }
                    return $data['description'];
                },
            ],

            //'url:url',
            //'internal_discount',
            [
                'attribute' => 'unit_price',
                'format' => 'raw',
                'contentOptions' => ['class' => 'amount'],
                'headerOptions' => ['class' => 'amount'],
                'footerOptions' => ['class' => 'amount'],
            ],
            /*
            [
                'attribute' => 'max_discount',
                'format' => 'raw',
                'contentOptions' => ['class' => 'amount'],
                'headerOptions' => ['class' => 'amount'],
                'footerOptions' => ['class' => 'amount'],
            ],
            [
                'attribute' => 'standard_discount',
                'format' => 'raw',
                'contentOptions' => ['class' => 'amount'],
                'headerOptions' => ['class' => 'amount'],
                'footerOptions' => ['class' => 'amount'],
            ],
            */
            //'vat_rate_code',
            //'notes:ntext',
                        
            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{details}',
				'buttons'=>[
					'details' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url);
						},
                    ],
            ],
            
            
        ],
    ]); ?>

</div>

