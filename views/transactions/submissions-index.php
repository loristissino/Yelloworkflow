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

    <?php if ($periodicalReport->isDraft): ?>
        <p>
            <?= Html::a(Yii::t('app', 'Create Transaction'), ['transaction-submissions/create', 'periodical_report'=> $periodicalReport->id], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif ?>

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
            'date',
            'description',
            [
                'attribute' => 'project_id',
                'format' => 'raw',
                'value' => 'project.submitterViewLink',
            ],
            [
                'label' => Yii::t('app', 'Postings'),
                'format' => 'raw',
                'value' => 'postingsView'
            ],
            [
                'attribute'=>'wf_status',
                'format'=>'raw',
                'value'=>'workflowLabel',
            ],

            //'event_id',
            //'notes',
            //'vat_number',
            //'vendor',
            //'wf_status',
            //'user_id',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {update} {delete}',
				'buttons'=>[
					'view' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['transaction-submissions/view', 'id'=>$model->id], [
                            'title'=>Yii::t('app', 'View'),
                            ]);
						},
					'update' => function ($url, $model) {
						if ( ! $model->canBeUpdated )
							return null;
						return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['transaction-submissions/update', 'id'=>$model->id], [
							'title' => Yii::t('app', 'Update'),
							]);
						},
					'delete' => function ($url, $model) {
						if ( ! $model->canBeUpdated )
							return null;
						return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['transaction-submissions/delete', 'id'=>$model->id], [
							'title' => Yii::t('app', 'Delete'),
                            'data-method'=>'post',
                            'data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
							]);
						},
				]
            ]
        ],
    ]); ?>

    <?php if ($periodicalReport->isDraft): ?>
        <?= Yii::t('app', 'With the selected transactions: ') ?>
        <?= Html::a("Confirm", ['transaction-submissions/process', 'action'=>'confirm', 'redirect'=>\yii\helpers\Url::toRoute(['periodical-report-submissions/view', 'id'=>$periodicalReport->id])], ['data-method'=>'post'])?>
    <?php endif ?>
    
    <?= Html::endForm();?>

    <?php else: ?>

    <p><?= Yii::t('app', 'No transactions.') ?></p>

    <?php endif ?>

</div>
