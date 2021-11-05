<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=Html::beginForm(['process'],'post');?>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            // ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'username',
                'headerOptions' => ['class' => 'hidden-sm'],
                'contentOptions' => ['class' => 'hidden-sm'],
            ],
            'first_name',
            'last_name',
            
            //'email:email',
            //'auth_key',
            //'access_token',
            //'otp_secret',
            //'status',
            //'created_at',
            //'updated_at',

            // ['class' => 'yii\grid\ActionColumn'],

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {update}',
            ]
        ],
    ]); ?>

    <?php Pjax::end(); ?>

    <?= Yii::t('app', 'With the selected users: ') ?>
    <?= Html::a(Yii::t('app', 'Fix Permissions'), ['process', 'action'=>'fixPermissions'], ['data-method'=>'post'])?>
    
    <?= Html::endForm();?> 


</div>
