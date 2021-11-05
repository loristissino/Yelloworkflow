<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Authorization */

$user_shown_on_title = $model->type == '-' ? $model->user->fullname: $model->type;

$this->title = Yii::t('app', 'Authorization «{identifier}» for {user}', ['identifier'=>$model->identifier, 'user'=>$user_shown_on_title]);

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Authorizations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="authorization-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Revoke'), ['revoke', 'id' => $model->id], [
            'class' => 'btn btn-warning',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to revoke this authorization?'),
                'method' => 'post',
            ],
        ]) ?>
        <?php if ($model->canBeDeleted()): ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this authorization?'),
                'method' => 'post',
            ],
        ]) ?>
        <?php endif ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'label' => 'Controller ID',
                'format' => 'raw',
                'value' => Html::a($model->controller_id, [$model->controller_id.'/index']),
            ],
            'action_id',
            'method',
            [
                'label' => 'User',
                'format' => 'raw',
                'value' => $model->type == '-' ? $model->user->viewLink : $model->type,
            ],
            [
                'label' => 'Role',
                'format' => 'raw',
                'value' => $model->role ? $model->role->viewLink : '',
            ],
            'begin_date',
            'end_date',
        ],
    ]) ?>

</div>
