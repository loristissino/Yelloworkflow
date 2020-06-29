<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PeriodicalReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $organizationalUnit->name . ' - ' . Yii::t('app', 'Accounting');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="periodical-report-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('/common_partials/_need_to_change_organizational_unit') ?>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'name',
                'format'=>'raw',
                'value'=>'viewLink',
            ],
            'begin_date',
            'end_date',
            [
                'attribute'=>'wf_status',
                'format'=>'raw',
                'value'=>'workflowLabel',
            ],
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view}',
				'buttons'=>[
					'view' => function ($url, $model) {
                        $icon = 'glyphicon glyphicon-' . ($model->isDraft ? 'pencil' : 'eye-open');
						return Html::a('<span class="' . $icon . '"></span>', $url);
						//return Html::a('view', $url); //['view', 'id'=>$model->id]);
						},
                ]
            ]
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
