<?php

use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PostingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Postings');
?>
<div class="posting-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'attribute' => Yii::t('app', 'Account'),
                'format' => 'raw',
                'value' => 'account.viewLink',
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
