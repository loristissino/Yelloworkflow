<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Markdown;

/* @var $this yii\web\View */
/* @var $model app\models\OrganizationalUnit */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Organizational Units'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="organizational-unit-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php /*= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) */ ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'rank',
            [
                'label' => 'Status',
                'value' => $model->status == 1 ? 'Active': 'Inactive',
            ],
            'name',
            'email:email',
            'url:url',
            'last_designation_date:date',
            [
                'attribute' => 'notes',
                'format' => 'raw',
                'value' => Markdown::process($model->notes, 'extra'),
            ],
            [
                'label' => Yii::t('app', 'Ceiling Amount'),
                'format' => 'raw',
                'value' => $model->formattedCeilingAmount,
            ],
            [
                'label' => Yii::t('app', 'Significant Ledgers'),
                'format' => 'raw',
                'value' => $model->significantLedgers,
            ],
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
    
    <h2><?= Yii::t('app', 'Users') ?></h2>
    <?= \app\components\UnorderedListWidget::widget([
        'introMessage'=>"{count,plural,=0{No user found} =1{One user found} other{# users found}}:",
        'items'=>$model->getUsers()->all(),
        'textProperty'=>'fullname',
        'link'=>'users/view',
    ]) ?>

    
    
    

</div>
