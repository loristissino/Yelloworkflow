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
            'id',
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
            [
                'attribute' => Yii::t('app', 'Amount'),
                'format' => 'raw',
                'value' => 'formattedAmount',
                'contentOptions' => ['class' => 'amount'],
                'headerOptions' => ['class' => 'amount'],
                'footerOptions' => ['class' => 'amount'],
            ],
        ],
    ]); ?>

</div>
