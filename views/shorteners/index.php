<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = Yii::t('app', 'Shorteners');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="petition-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Shortener'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?= Yii::t('app', 'Latest shortenings.') ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'label'=>Yii::t('app', 'Title'),
                'format'=>'raw',
                'value'=>function($model){
                    return Html::a($model['title'], $model['url']);
                }
            ],
            [
                'label'=>Yii::t('app', 'Short Url'),
                'format'=>'raw',
                'value'=>function($model){
                    return Html::a($model['shorturl'], $model['shorturl']);
                }
            ]
        ],
    ]); ?>

</div>
