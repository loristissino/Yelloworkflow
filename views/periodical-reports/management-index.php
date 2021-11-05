<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
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
                'template'=>'{view} {remind}',
				'buttons'=>[
					'view' => function ($url, $model) {
                        if (! $model->isDraft) {
                            $icon = 'glyphicon glyphicon-' . ($model->isDraft ? 'pencil' : 'eye-open');
                            return Html::a('<span class="' . $icon . '"></span>', $url);
                            }
                        },
                ]
            ]


        ],
    ]); ?>


</div>
