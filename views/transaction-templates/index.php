<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TransactionTemplateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Transaction Templates');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Back End'), 'url'=>['backend/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-template-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Transaction Template'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute'=>'rank',
                'format'=>'raw',
                'headerOptions'=>['class'=>'narrow_column'],
                'contentOptions' => ['class' => 'narrow_column'],
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'style'=>'width: 40px',
                ],
            ],
            [
                'attribute'=>'organizational_unit',
                'format'=>'raw',
                'value'=>'organizationalUnit.viewLink',
            ],
            [
                'attribute'=>'title',
                'format'=>'raw',
                'value'=>'infoView',
            ],
            //'description',
            [
                'attribute'=>'needs_attachment',
                'format'=>'raw',
                'label'=>'Attachment?',
                'value'=>'needsAttachmentView',
                'headerOptions'=>['title'=>'Is it required that a file is attached?', 'class'=>'narrow_column'],
                'contentOptions' => ['class' => 'narrow_column'],
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'style'=>'width: 40px',
                ],
            ],
            [
                'attribute'=>'needs_project',
                'format'=>'raw',
                'label'=>'Project?',
                'value'=>'needsProjectView',
                'headerOptions'=>['title'=>'Is it required that a project is specified?', 'class'=>'narrow_column'],
                'contentOptions' => ['class' => 'narrow_column'],
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'style'=>'width: 40px',
                ],
            ],

            [
                'attribute'=>'needs_vendor',
                'format'=>'raw',
                'label'=>'Vendor?',
                'value'=>'needsVendorView',
                'headerOptions'=>['title'=>'Is it required that a vendor is specified?', 'class'=>'narrow_column'],
                'contentOptions' => ['class' => 'narrow_column'],
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'style'=>'width: 40px',
                ],
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {update}',
            ]
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
