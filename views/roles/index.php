<?php

use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\RoleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Roles');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Role'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'name',
                'headerOptions' => ['class' => 'hidden-sm'],
                'contentOptions' => ['class' => 'hidden-sm'],
            ],
            'description',
            'permissions',
            [
                'attribute'=>'status',
                'format'=>'raw',
                'label'=>Yii::t('app', 'Status'),
                'value'=>'statusView',
                'headerOptions'=>['title'=>'Is it required to be a member for this role?', 'class'=>'narrow_column'],
                'contentOptions' => ['class' => 'narrow_column'],
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'style'=>'width: 40px',
                ],
            ],
            //'email:email',

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {update}',
            ]
        ],
    ]); ?>

</div>

<hr />

<?= Html::a(Yii::t('app', 'Renewals Check'), ['roles/renewals']) ?>

