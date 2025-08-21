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
            [
                'attribute' => 'last_designation_date',
                'format' => 'date',
                'contentOptions' => function ($model, $key, $index, $column) {
                    $date = new \DateTime($model->last_designation_date);
                    $twoYearsAgo = (new \DateTime())->modify('-2 years');
                    
                    if ($date < $twoYearsAgo) {
                        return ['style' => 'background-color: yellow;'];
                    }
                    
                    return [];
                },
            ],
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
