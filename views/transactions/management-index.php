<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/*
 */
$this->title = Yii::t('app', 'Transactions');
//$this->params['breadcrumbs'][] = $this->title;

?>

<div class="transaction-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <?php if (($dataProvider->count > 0)): ?>

    <?=Html::beginForm(['process'],'post');?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
			['class' => 'yii\grid\CheckboxColumn'],

            // 'id',
            // 'periodical_report_id',
            [
                'label'=>Yii::t('app', 'Date'),
                'attribute'=>'date',
                'format'=>'raw',
                'value'=>function($data) {
                    return Yii::$app->formatter->asDate($data['date']);
                },
            ],
            'description',
            /*
            [
                'attribute' => 'project_id',
                'format' => 'raw',
                'value' => 'project.title',
            ],
            */
            [
                'label' => 'Postings',
                'format' => 'raw',
                'value' => 'postingsViewWithoutLink',
            ],
            [
                'attribute'=>'wf_status',
                'format'=>'raw',
                'value'=>'workflowLabel',
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view}',
				'buttons'=>[
					'view' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['transactions-management/view', 'id'=>$model->id], [
                            'title'=>Yii::t('app', 'View'),
                            ]);
						},
				]
            ]
        ],
    ]); ?>
    
    <?= Html::endForm();?>

    <?php else: ?>

    <p><?= Yii::t('app', 'No transactions.') ?></p>

    <?php endif ?>

</div>
