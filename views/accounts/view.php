<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Account */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Back End'), 'url'=>['backend/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="account-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php if ($model->getPostings()->count()==0): ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
        <?php endif ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'organizational_unit_id',
            'rank',
            'name',
            'status',
            'code',
            'debits_header',
            'credits_header',
            [
                'attribute'=>'created_at',
                'format'=>'raw',
                'value'=>function($data) {
                    return sprintf('%s %s', Yii::$app->formatter->asDate($data['created_at']), Yii::$app->formatter->asTime($data['created_at']));
                },
            ],
            [
                'attribute'=>'updated_at',
                'format'=>'raw',
                'value'=>function($data) {
                    return sprintf('%s %s', Yii::$app->formatter->asDate($data['updated_at']), Yii::$app->formatter->asTime($data['updated_at']));
                },
            ],
        ],
    ]) ?>

</div>
