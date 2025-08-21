<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\QuestionnaireResponseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Questionnaires and Responses');

$this->params['breadcrumbs'][] = $this->title;

?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="questionnaire-responses-index">

    <h2><?= Yii::t('app', 'Active questionnaires') ?></h2>

    <?= GridView::widget([
        'dataProvider' => $questionnairesDataProvider,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            'title',
            'updated_at:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{fill}',
				'buttons'=>[
					'fill' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-list-alt"></span>', ['questionnaire-responses/fill', 'id'=>$model->id], ['title'=>Yii::t('app', 'Fill')]);
                    },
				]
            ],
        ],
    ]); ?>

<div class="questionnaire-responses-index">

    <h2><?= Yii::t('app', 'My responses') ?></h2>

    <?= GridView::widget([
        'dataProvider' => $responsesDataProvider,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            'questionnaire',
            [
                'attribute'=>'wf_status',
                'format'=>'raw',
                'value'=>'workflowLabel',
            ],
            'label',
            'updated_at:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
				'buttons'=>[
					'view' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['questionnaire-responses/view-response', 'id'=>$model->id], ['title'=>Yii::t('app', 'View')]);
                    },
					'update' => function ($url, $model) {
                        if (!$model->isDraft){
                            return '';
                        } 
						return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['questionnaire-responses/fill', 'id'=>$model->questionnaire_id, 'response'=>$model->id], ['title'=>Yii::t('app', 'Update')]);
					},
					'delete' => function ($url, $model) {
                        if (!$model->isDraft){
                            return '';
                        } 
						return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['questionnaire-responses/delete', 'id'=>$model->id], [
                            'data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
                            'data-method'=>'POST',
                            'title'=>Yii::t('app', 'Delete')
                            ]
                        );
					},
				]
            ],
        ],
    ]); ?>

</div>
