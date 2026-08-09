<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Shortener */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shorteners'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="shortener-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'keyword',
            [
                'attribute'=>'shorturl',
                'format'=>'raw',
                'value'=>Html::a($model->shorturl, $model->shorturl, ['title'=>$model->title]),
            ],
            'url',
            'title',
        ],
    ]) ?>

</div>
