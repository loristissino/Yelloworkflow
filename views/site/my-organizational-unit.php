<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = Yii::t('app', 'My Organizational Unit');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-my-organizational-unit">
    <h1><?= Html::encode($this->title) ?> «<?= $ou ?>»</h1>

    <p><?= Yii::t('app', 'Email:') ?> <b><?= $ou->email ?></b></p>
    <p><?= Yii::t('app', 'Last Update:') ?> <b><?= Yii::$app->formatter->asDate($ou->last_designation_date) ?></b></p>
    
    <h2><?= Yii::t('app', 'People') ?></h2>
    
    <?= $this->render('/common_partials/_need_to_change_organizational_unit') ?>
    

        <?= GridView::widget([
        'dataProvider' => $peopleDataProvider,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            'user.first_name',
            'user.last_name',
            'role',
            'user.last_renewal',
            [
                'label'=>Yii::t('app', 'Last Action'),
                'attribute' =>  'user.last_action_at',
                'format' => 'datetime'
            ]
        ],
    ]); ?>

    <h2><?= Yii::t('app', 'Last Activities') ?></h2>

    <?= GridView::widget([
        'dataProvider' => $activitiesDataProvider,
        'columns' => [
            [
                'attribute' => 'happened_at',
                'format' => ['datetime', 'php:Y-m-d H:i'],
            ],
            'activity_type',
            'first_name',
            'last_name',
            //'organizational_unit_id',
            //'name',
            //'role_id',
            [
                'label'=>Yii::t('app', 'Role'),
                'attribute'=>'role_description',
            ],
        ],
    ]); ?>
    
</div>
