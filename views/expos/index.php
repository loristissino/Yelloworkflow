<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ExpoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Expos');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expo-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Expo'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'begin_date',
            'end_date',
            'name',
            'city',
            //'organizational_unit_id',
            //'created_at',
            //'updated_at',
            [
                'attribute'=>'wf_status',
                'format'=>'raw',
                'value'=>'workflowLabel',
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {update} {delete}',
				'buttons'=>[
					'view' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['expos/view', 'id'=>$model->id], [
                            'title'=>Yii::t('app', 'View'),
                            ]);
						},
					'update' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['expos/update', 'id'=>$model->id], [
							'title' => Yii::t('app', 'Update'),
							]);
						},
					'delete' => function ($url, $model) {
						if ( ! $model->canBeDeleted )
							return null;
						return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['expos/delete', 'id'=>$model->id], [
							'title' => Yii::t('app', 'Delete'),
                            'data-method'=>'post',
                            'data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
							]);
						},
				]
            ]

        ],
    ]); ?>


</div>
