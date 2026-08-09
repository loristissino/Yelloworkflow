<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Product */

$this->title = $model->description;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'sku',
            'isbn',
            [
                'label' => Yii::t('app', 'Cover'),
                'format'=> 'raw',
                'value' => $model->isbn ? Html::img('https://covers.openlibrary.org/b/isbn/' . $model->isbn . '-M.jpg', ['title'=>'Cover courtesy openlibrary.org', 'alt'=>$model->description]) : ''
            ],
            
            //<img src="9780385533225" />
            'author',
            'long_description',
            'url:url',
            'unit_price',
            'max_discount',
            'standard_discount',
            'internal_discount',
            'vat_rate_code',
            'notes:ntext',
            [
                'label' => Yii::t('app', 'Organizational Unit'),
                'format' => 'raw',
                'value' => $model->organizational_unit_id ? $model->organizationalUnit->viewLink : '',
            ],
        ],
    ]) ?>

</div>
