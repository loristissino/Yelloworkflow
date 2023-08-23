<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Petition */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Petitions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="petition-view">

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
            'slug',
            'title',
            'target:ntext',
            'introduction:ntext',
            'picture_url:url',
            'request:ntext',
            'updates:ntext',
            'promoted_by:raw',
            'wf_status',
            'created_at',
            'updated_at',
            'launched_at',
            'expired_at',
            'goal',
        ],
    ]) ?>
    
    <hr>
    
    <p>
        <?= Html::a(Yii::t('app', 'Full view with all signatures'), ['petition-signatures/list', 'slug'=>$model->slug]) ?><br>
        <?= Html::a(Yii::t('app', 'Full view with all signatures, without messages'), ['petition-signatures/list', 'slug'=>$model->slug, 'withMessages'=>'false']) ?>
    </p>

</div>
