<?php

use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\AffiliationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Affiliations');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="affiliation-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Affiliation'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            /*
            [
                'attribute'=>'first_name',
                'value'=>'user.first_name',
            ],
            [
                'attribute'=>'last_name',
                'value'=>'user.last_name',
            ],
            */
            [
                'attribute'=>'full_name',
                'format'=>'raw',
                'value'=>'user.viewLink',
            ],
            [
                'attribute'=>'organizational_unit',
                'format'=>'raw',
                'value'=>'organizationalUnit.viewLink',
            ],
            [
                'attribute'=>'role',
                'format'=>'raw',
                'value'=>'role.viewLink',
            ],
            'email',
            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {update}',
            ]
        ],
    ]); ?>

</div>
