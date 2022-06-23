<?php

use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TransactionTemplatePostingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-template-posting-index">

    <h2><?= Yii::t('app', 'Template Postings') ?></h2>

    <p>
        <?= Html::a(Yii::t('app', 'Create Transaction Template Posting'), ['transaction-template-postings/create', 'template'=>$model->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'transaction_template_id',
            'rank',
            //'account_id',
            'account.name',
            [
                'attribute'=>'dc',
                'format'=>'raw',
                'value'=>'viewDC',
            ],
            'amount',

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {update} {delete}',
				'buttons'=>[
					'view' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['transaction-template-postings/view', 'id'=>$model->id], [
							'title' => Yii::t('yii', 'Reserve'),
							]);
						},
					'update' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['transaction-template-postings/update', 'id'=>$model->id], [
							'title' => Yii::t('yii', 'Reserve'),
							]);
						},
					'delete' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['transaction-template-postings/delete', 'id'=>$model->id], [
								'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
								'data-method' => 'post',
							]);
						},
				]
            ]


        ],
    ]); ?>

</div>
