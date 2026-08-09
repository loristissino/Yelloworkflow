<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Product */

$this->title = $model->description;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products Management'), 'url' => ['manage']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Clone'), ['clone', 'id' => $model->id], [
            'class' => 'btn btn-info',
            'data' => [
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'digital_receipt_type_id',
            [
                'label' => Yii::t('app', 'Organizational Unit'),
                'format' => 'raw',
                'value' => $model->organizational_unit_id ? $model->organizationalUnit->viewLink : '',
            ],
            'sku',
            'ecommerce_code',
            'rank',
            'status',
            'isbn',
            'author',
            'description',
            'long_description',
            'url:url',
            'unit_price',
            'max_discount',
            'standard_discount',
            'internal_discount',
            'vat_rate_code',
            'notes:ntext',
            'extra_info_required',
            'requires_sealing',
            [
                'attribute'=>'sales_account_id',
                'format'=>'raw',
                'value'=>$model->salesAccount
            ],
            [
                'attribute'=>'discounts_account_id',
                'format'=>'raw',
                'value'=>$model->discountsAccount
            ],
            [
                'attribute'=>'returns_account_id',
                'format'=>'raw',
                'value'=>$model->returnsAccount
            ],
        ],
    ]) ?>

</div>
