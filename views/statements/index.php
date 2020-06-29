<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\AccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Statements');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'rank',
                'format'=>'raw',
                'headerOptions'=>['class'=>'narrow_column'],
                'contentOptions' => ['class' => 'narrow_column'],
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'style'=>'width: 40px',
                ],
            ],
            'name',
            [
                'attribute'=>'status',
                'format'=>'raw',
                'label'=>'Active?',
                'value'=>'statusView',
                'headerOptions'=>['class'=>'narrow_column'],
                'contentOptions' => ['class' => 'narrow_column'],
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'style'=>'width: 40px',
                ],
            ],
            //'code',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view}',
            ]
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
