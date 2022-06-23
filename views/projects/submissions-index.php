<?php

use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $organizationalUnit->name . ' - ' . Yii::t('app', 'Projects');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Project'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= $this->render('/common_partials/_need_to_change_organizational_unit') ?>
    
    <?php if (($dataProvider->count > 0) or (sizeof(Yii::$app->request->queryParams)>0)): ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            'title',
            //'description:ntext',
            //'co_hosts:ntext',
            //'partners:ntext',
            'period',
            'place',
            //'wf_status',
            [
                'attribute'=>'wf_status',
                'format'=>'raw',
                'value'=>'workflowLabel',
            ],
            //'organizational_unit_id',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {update}',
				'buttons'=>[
					'update' => function ($url, $model) {
						return $model->isDraft ? Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url) : null;
						},
                ]
            ]

        ],
    ]); ?>

    <?php endif ?>

</div>
