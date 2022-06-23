<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Projects Management');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-index">

    <h1><?= Html::encode($this->title) ?></h1>

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
            [
                'attribute'=>'organizational_unit',
                'format'=>'raw',
                'value'=>'organizationalUnit.viewLink',
            ],

            //'created_at',
            //'updated_at',

                ['class' => 'yii\grid\ActionColumn',
                    'template'=>'{view} {workflow}',
                    'buttons'=>[
                        'view' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['projects-management/view', 'id'=>$model->id], [
                                'title'=>Yii::t('app', 'View'),
                                ]);
                            },
                        'workflow' => function ($url, $model) {
                            if (Yii::$app->user->hasAuthorizationFor('workflow')) {
                                $icon = 'glyphicon glyphicon-calendar';
                                return Html::a(sprintf('<span class="%s" style="color:#800080" title="%s"></span>', $icon, Yii::t('app', 'Edit Workflow Status')), ['workflow/update', 'type'=>get_class($model), 'id'=>$model->id, 'return'=>Url::current()]);
                                }
                            },
                    ],
                    'contentOptions'=>['style'=> 'width: 80px'],
                ]
        ],
    ]); ?>

</div>
