<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$totalAmount = 0;

foreach($dataProvider->models as $expense) {
    $totalAmount += $expense->amount;
}

$is_draft = $project->isDraft;

$columns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'attribute'=>'expense_type',
        'label' => Yii::t('app', 'Expense Type'),
        'format'=>'raw',
        'value'=>'expenseType.name',
    ],
    'description',
    [
        'attribute'=>'amount',
        'format'=>'raw',
        'value'=>'formattedAmount',
        'contentOptions' => ['class' => 'amount'],
        'headerOptions' => ['class' => 'amount'],
        'footer' => Yii::$app->formatter->asCurrency($totalAmount),
        'footerOptions' => ['class' => 'amount'],
    ],
    'notes:ntext',
];

if ($is_draft) {
    $columns[] = 
            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{update} {delete}',
				'buttons'=>[
					'delete' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['planned-expenses/delete', 'id'=>$model->id], [
								'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
								'data-method' => 'post',
							]);
						},
					'update' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['planned-expenses/update', 'id'=>$model->id]);
						},
                    ]
            ]
            ;
}

?>
<div class="planned-expense-index">

    <h2><?= Yii::t('app', 'Planned Expenses') ?></h2>

    <?php if ($is_draft): ?>
        <p>
            <?= Html::a(Yii::t('app', 'Create Planned Expense'), ['planned-expenses/create', 'project'=>$project->id], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif ?>

    <?php if ($dataProvider->count > 0): ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'showFooter' => true,
        'footerRowOptions' => ['class'=>'grid_footer'],
        'columns' => $columns,
    ]); ?>


    <?php endif ?>

</div>
