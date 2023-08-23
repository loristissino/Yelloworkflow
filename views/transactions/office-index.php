<?php

use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/*
 */

$controller = Yii::$app->controller->id;
 
switch ($controller) {
    case 'office-transactions':
        $this->title = Yii::t('app', 'Office Transactions');
        break;
    case 'fast-transactions':
        $this->title = Yii::t('app', 'Fast Transactions');
        break;
} 
 
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="transaction-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if($controller == 'office-transactions'): ?>
        <p>
            <?= Html::a(Yii::t('app', 'Create Transaction'), ['office-transactions/create'], ['class' => 'btn btn-success']) ?>
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
            [
                'label'=>Yii::t('app', 'Date'),
                'attribute'=>'date',
                'format'=>'raw',
                'value'=>function($data) {
                    return Yii::$app->formatter->asDate($data['date']);
                },
            ],
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
                'label' => Yii::t('app', 'Project'),
                'format' => 'raw',
                'value' => 'project'
            ],
            [
                'attribute'=>'wf_status',
                'format'=>'raw',
                'value'=>'workflowLabel',
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view}',
				'buttons'=>[
					'view' => function ($url, $model) use ($controller) {
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ["$controller/view", 'id'=>$model->id], [
                            'title'=>Yii::t('app', 'View'),
                            ]);
						},
                    /*
					'update' => function ($url, $model)  use ($controller) {
						if ( ! $model->getCanBeUpdated('prepared') )
							return null;
						return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ["$controller/update", 'id'=>$model->id], [
							'title' => Yii::t('app', 'Update'),
							]);
						},
                    */
				]
            ]
        ],
    ]); ?>

    <?php if ($controller == 'office-transactions'): ?>
        <?= Yii::t('app', 'With the selected transactions: ') ?>
        <?= Html::a(Yii::t('app', 'Notify'), ['process', 'action'=>'notify'], ['data-method'=>'post'])?>
                
    <?php endif ?>
    
    <?= Html::endForm();?>
    
    <?php else: ?>

    <p><?= Yii::t('app', 'No transactions.') ?></p>

    <?php endif ?>
    
    <hr>
    
    <p>
        <?php if($recorded=='extra'): ?>
            <?= Html::a(Yii::t('app', 'Ordinary transactions'), ['index']) ?>
        <?php else: ?>
            <?= Html::a(Yii::t('app', '«Extra» transactions'), ['index', 'recorded'=>'extra']) ?>
        <?php endif ?>
    </p>

</div>
