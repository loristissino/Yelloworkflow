<?php

use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\AuthorizationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Authorizations');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authorization-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Authorization'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'controller_id',
            'action_id',
            'method',
            'type',
            [
                'attribute'=>'user',
                'format'=>'raw',
                'value'=>'user.viewLink',
            ],
            // 'begin_date',
            // 'end_date',
            //'role_id',
            [
                'attribute'=>'role',
                'format'=>'raw',
                'value'=>'role.viewLink',
            ],
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {update}',
            ]
        ],
    ]); ?>

</div>
