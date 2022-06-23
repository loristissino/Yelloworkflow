<?php

use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\OrganizationalUnitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Organizational Units');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organizational-unit-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Organizational Unit'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'rank',
            'name',
            'email:email',
            [
                'attribute' => 'ceiling_amount',
                'format' => 'raw',
                'value' => 'formattedCeilingAmount',
                'contentOptions' => ['class' => 'amount'],
                'headerOptions' => ['class' => 'amount'],
                'footerOptions' => ['class' => 'amount'],
            ],
            'last_designation_date:date',
            //'url:url',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {update}',
            ]
        ],
    ]); ?>

</div>

<div><?= Html::a(Yii::t('app', 'Show / Hide organizational units depending of being active'), ['organizational-units/index', 'active'=>($active=='true'?'false':'true')]) ?></div>
