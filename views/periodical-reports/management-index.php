<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PeriodicalReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Financial Reports Management');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="periodical-report-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php //= Html::a(Yii::t('app', 'Create Periodical Report'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Create Periodical Reports'), ['create-reports'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'organizational_unit',
                'format'=>'raw',
                'value'=>'organizationalUnit.viewLink',
            ],
            'name',
            [
                'label'=>Yii::t('app', 'Begin Date'),
                'attribute'=>'begin_date',
                'format'=>'raw',
                'value'=>function($data) {
                    return Yii::$app->formatter->asDate($data['begin_date']);
                },
            ],
            [
                'label'=>Yii::t('app', 'End Date'),
                'attribute'=>'end_date',
                'format'=>'raw',
                'value'=>function($data) {
                    return Yii::$app->formatter->asDate($data['end_date']);
                },
            ],
            [
                'attribute'=>'wf_status',
                'format'=>'raw',
                'value'=>'workflowLabel',
            ],
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {edit} {log} {workflow}',
				'buttons'=>[
					'view' => function ($url, $model) {
                        if ($model->canBeSeenInManagementView) {
                            $icon = 'glyphicon glyphicon-eye-open';
                            return Html::a(sprintf('<span class="%s" title="%s"></span>', $icon, Yii::t('app', 'View')), $url);
                            }
                        },
					'edit' => function ($url, $model) {
                        if (Yii::$app->user->hasAuthorizationFor('workflow') and !$model->canBeSeenInManagementView) {
                            $icon = 'glyphicon glyphicon-dashboard';
                            return Html::a(sprintf('<span class="%s" style="color:#FFA500" title="%s"></span>', $icon, Yii::t('app', 'Properties')), ['update',  'id'=>$model->id, 'return'=>Url::current()]);
                            }
                        },
					'log' => function ($url, $model) {
                        $icon = 'glyphicon glyphicon-list-alt';
                        return Html::a(sprintf('<span class="%s" title="%s"></span>', $icon, Yii::t('app', 'Workflow Log')), ['log', 'id'=>$model->id]);
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
