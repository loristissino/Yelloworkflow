<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Project */

$transitions = $model->getAuthorizedTransitions();
$currentStatus = $model->getWorkflowStatus()->getId();

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Projects'), 'url' => ['project-submissions/list']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', $model->isApproved ? 'Approved Projects': 'Completed Projects'), 'url' => ['project-submissions/list', 'status'=>$model->isApproved ? 'approved': 'completed']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="project-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            'description:ntext',
            'co_hosts:ntext',
            'partners:ntext',
            'period',
            'place',
            [
                'label' => Yii::t('app', 'Organizational Unit'),
                'format' => 'raw',
                'value' => $model->organizationalUnit->viewLinkWithEmail,
            ],
        ],
    ]) ?>

</div>

<?= Html::a(Yii::t('app', 'Clone Project'), ['clone', 'id' => $model->id], [
    'class' => 'btn btn-info',
    'data' => [
        'method' => 'post',
    ],
]) ?>
