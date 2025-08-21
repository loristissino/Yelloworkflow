<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MainOuViewedActivitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Organizational Units\' Main Activities');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main-ou-viewed-activity-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'happened_at',
                'format' => ['datetime', 'php:Y-m-d H:i'],
            ],
            'activity_type',
            'first_name',
            'last_name',
            //'organizational_unit_id',
            'name',
            //'role_id',
            [
                'label'=>Yii::t('app', 'Role'),
                'attribute'=>'role_description',
            ],
        ],
    ]); ?>


</div>
