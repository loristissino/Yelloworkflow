<?php

use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ActivitytSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Activities');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute'=>'happened_at',
                'format'=>'raw',
                'value'=>function($data) {
                    return sprintf('%s %s', Yii::$app->formatter->asDate($data['happened_at']), Yii::$app->formatter->asTime($data['happened_at']));
                },
            ],
            [
                'attribute'=>'full_name',
                'format'=>'raw',
                'value'=>'user.viewLink',
            ],
            'activity_type',
            'model',
            'model_id',
            //'info:ntext',
            [
                'attribute'=>'authorization_id',
                'format'=>'raw',
                'value'=>'authorization.viewLink',
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view}',
            ]
        ],
    ]); ?>

</div>
