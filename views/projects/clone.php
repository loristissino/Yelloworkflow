<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Project */

$currentStatus = $model->getWorkflowStatus()->getId();

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', Yii::$app->controller->id == 'project-submissions' ? 'Projects': 'Projects Management'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url'=>['view', 'id'=>$model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Cloning');
\yii\web\YiiAsset::register($this);

$transitions = $model->getAuthorizedTransitions();
$currentStatus = $model->getWorkflowStatus()->getId();

?>


<?php if ($model->organizationalUnit->hasLoggedInUser()): ?>

    <?php if (in_array($currentStatus, ['ProjectWorkflow/questioned'])): ?>
        <p>💡 <?= Yii::t('app', 'When a project has been questioned do not clone it, but update it (or answer to comments) and re-submit it.') ?></p>
        <?= Html::a(Yii::t('app', 'Reset to draft the current project'), ['change', 'id' => $model->id, 'status'=>'ProjectWorkflow/draft'], [
            'class' => 'btn btn-info',
            'style' => 'background-color: gray; border-color: gray',
            'data' => [
                'method' => 'post',
            ],
        ]) ?>
    <?php elseif (in_array($currentStatus, ['ProjectWorkflow/submitted', 'ProjectWorkflow/approved'])): ?>
        <p>💡 <?= Yii::t('app', 'When a project has already been submitted and you need to update it, do not clone it, but reset it to draft, update it and re-submit it.') ?></p>
        <?= Html::a(Yii::t('app', 'Reset to draft the current project'), ['change', 'id' => $model->id, 'status'=>'ProjectWorkflow/draft'], [
            'class' => 'btn btn-info',
            'style' => 'background-color: gray; border-color: gray',
            'data' => [
                'method' => 'post',
            ],
        ]) ?>
    <?php else: ?>
        <?php if (in_array($currentStatus, ['ProjectWorkflow/draft'])): ?>
            <p>🔔 <?= Yii::t('app', 'This project is a draft. Do you really want to clone it? Consider updating it instead...') ?></p>
        <?php endif ?>
        <?= Html::a(Yii::t('app', 'Clone Project'), ['clone', 'id' => $model->id], [
            'class' => 'btn btn-info',
            'data' => [
                'method' => 'post',
            ],
        ]) ?>
    <?php endif ?>
<?php endif ?>


<div class="project-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'description:ntext',
            'wf_status',
            [
                'label' => Yii::t('app', 'Activities'),
                'format' => 'raw',
                'value' => Html::a(Yii::t('app', 'Workflow Log'), ['log', 'id'=>$model->id]),
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

</div>

