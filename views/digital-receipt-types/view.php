<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DigitalReceiptType */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Digital Receipt Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="digital-receipt-type-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'explanation',
            'description',
            'label',
            'issued_text',
            'voiding_text',
            'return_text',
            'sequential_number_code',
            'status',
            'amount_soft_limit',
            'amount_hard_limit',
            'color',
            [
                'attribute'=>'validator',
                'format'=>'raw',
                'value'=>function($model) {
                    $html = $model->validator;
                    if (method_exists($model->validator, 'getConfiguration')){
                        $html .= ' ' . Html::a(Yii::t('app', 'Configuration'), ['api-config', 'id'=>$model->id]);
                    }
                    return $html;
                }
            ],
            'environment',
        ],
    ]) ?>

</div>
