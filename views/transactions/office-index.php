<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/*
 */
$this->title = Yii::t('app', 'Office Transactions');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="transaction-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Transaction'), ['office-transactions/create'], ['class' => 'btn btn-success']) ?>
    </p>

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
                'label' => Yii::t('app', 'Organizational Unit'),
                'format' => 'raw',
                'value' => 'periodicalReport.organizationalUnit.viewLink',
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

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view}',
				'buttons'=>[
					'view' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['office-transactions/view', 'id'=>$model->id], [
                            'title'=>Yii::t('app', 'View'),
                            ]);
						},
				]
            ]
        ],
    ]); ?>

    <?= Yii::t('app', 'With the selected transactions:') ?>
    <?= Html::a(Yii::t('app', 'Notify'), ['process', 'action'=>'notify'], ['data-method'=>'post'])?>
    
    <?= Html::endForm();?>
    
    <?= Html::endForm();?>

    <?php else: ?>

    <p><?= Yii::t('app', 'No transactions.') ?></p>

    <?php endif ?>

</div>
