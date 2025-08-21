<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $model app\models\Activity */

$this->title = Yii::t('app', 'Activity') . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$object = null;
if ($model->model_id) {
    $object = $model->model::findOne($model->model_id);
}

?>
<div class="activity-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute'=>'happened_at',
                'format'=>'raw',
                'value'=>function($data) {
                    return sprintf('%s %s', Yii::$app->formatter->asDate($data['happened_at']), Yii::$app->formatter->asTime($data['happened_at']));
                },
            ],
            [
                'label' => 'User',
                'format' => 'raw',
                'value' => $model->user_id ? $model->user->viewLink : '',
            ],
            'activity_type',
            'model',
            'model_id',
            [
                'label' => 'Object',
                'format' => 'raw',
                'value' => $object ? $object->viewLink: '',
            ],
            [
                'label' => 'Content',
                'format' => 'raw',
                'value' => $model->info,
            ],
            [
                'label' => 'Authorization',
                'format' => 'raw',
                'value' => $model->authorization_id ? $model->authorization->viewLink : '',
            ],
            [
                'label' => 'Workflow',
                'format' => 'raw',
                'value' => function($model) use($object) {
                    if (!Yii::$app->user->hasAuthorizationFor('workflow') or !$object or !$object->hasProperty('wf_status')) {
                        return '';
                    }
                    return Html::a(Yii::t('app', 'Edit Workflow Status'), ['workflow/update', 'type'=>$model->model, 'id'=>$model->model_id, 'return'=>Url::current()]);
                }
            ],
        ],
    ]) ?>

</div>
