<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PetitionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Petitions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="petition-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Petition'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'slug',
            'title',
            'target:ntext',
            'introduction:ntext',
            //'picture_url:url',
            //'request:ntext',
            //'updates:ntext',
            //'wf_status',
            //'created_at',
            //'updated_at',
            //'launched_at',
            //'expired_at',
            //'goal',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
