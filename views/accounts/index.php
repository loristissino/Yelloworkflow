<?php

use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\AccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Accounts');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Back End'), 'url'=>['backend/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Account'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
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
                'attribute'=>'organizational_unit',
                'format'=>'raw',
                'value'=>'organizationalUnit',
            ],
            [
                'attribute'=>'represents',
                'format'=>'raw',
                'label'=>'Represents',
                'value'=> 'representsView',
                'headerOptions'=>['title'=>'Represents', 'class'=>'narrow_column'],
                'contentOptions' => ['class' => 'narrow_column'],
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'style'=>'width: 40px',
                ],
            ],
            [
                'attribute'=>'enforced_balance',
                'format'=>'raw',
                'label'=>'EB',
                'headerOptions'=>['title'=>'Enforced Balance: D=Debits, C=Credits', 'class'=>'narrow_column'],
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
                'template'=>'{view} {update}',
            ]

        ],
    ]); ?>

</div>
