<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PetitionSignatureSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Petition Signatures');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="petition-signature-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'petition_id',
            'email:email',
            'first_name',
            'last_name',
            'message:ntext',
            //'accepted_terms',
            //'created_at',
            //'updated_at',
            'confirmed_at',
            //'validated_at',

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {update}',
            ]
        ],
    ]); ?>


</div>
