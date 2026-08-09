<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use app\models\DigitalReceipt;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\DigitalReceipt */

$this->title = $model->title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Digital Receipts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="digital-receipt-view">

    <h1><?= Html::encode($this->title) ?></h1>

    
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute'=>'sequential_number',
                'format'=>'raw',
                'value' => function ($model) {
                    $html = $model->completeSequentialNumber;
                    if ($model->sequential_number) {
                        $html .= ' ' . Html::a('📃', ['/site/digital-receipt', 'id'=>$model->client_id], ['style'=>'text-decoration: none']);
                    }
                    return $html;
                },
            ],
            [
                'label' => 'api_response',
                'format'=>'raw',
                'value' => function ($model) use ($items) {
                    return Html::tag('pre', json_encode($items, JSON_PRETTY_PRINT));
                },
            ],
        ],
    ]) ?>

</div>
