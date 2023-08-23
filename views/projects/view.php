<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use app\models\PlannedExpense;
use app\models\PlannedExpenseSearch;
use app\models\ProjectComment;
use app\models\ProjectCommentSearch;
use app\models\Posting;
use app\models\PostingSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Project */

$transitions = $model->getAuthorizedTransitions();
$currentStatus = $model->getWorkflowStatus()->getId();

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', Yii::$app->controller->id == 'project-submissions' ? 'Projects': 'Projects Management'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);


$plannedExpenseSearchModel = new PlannedExpenseSearch();
$plannedExpenseDataProvider = $plannedExpenseSearchModel->search(Yii::$app->request->queryParams, PlannedExpense::find()->ofProject($model));

if ($model->allowsComments) {
    $commentSearchModel = new ProjectCommentSearch();
    $commentDataProvider = $commentSearchModel->search(Yii::$app->request->queryParams, ProjectComment::find()->forProject($model));
    $commentDataProvider->sort->defaultOrder = ['created_at' => SORT_DESC];
}

if (!$model->isDraft) {
    $postingSearchModel = new PostingSearch();
    $postingDataProvider = $postingSearchModel->search(Yii::$app->request->queryParams, Posting::find()->joinWith('transaction')->withRealAccount(false)->rejected(false)->relatedToProject($model));
}

?>
<div class="project-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= ($currentStatus=='ProjectWorkflow/draft' and $model->organizationalUnit->hasLoggedInUser()) ? Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) : '' ?>

    <?= $this->render('/workflow/_workflowbuttons', [
        'model' => $model,
        'transitions' => $transitions
    ]) ?>
    </p>
    
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'description:ntext',
            'bond:ntext',
            'co_hosts:ntext',
            'partners:ntext',
            'period',
            'place',
            'wf_status',
            [
                'label' => Yii::t('app', 'Activities'),
                'format' => 'raw',
                'value' => Html::a(Yii::t('app', 'Workflow Log'), ['log', 'id'=>$model->id]),
            ],
            [
                'label' => Yii::t('app', 'Organizational Unit'),
                'format' => 'raw',
                'value' => $model->organizationalUnit->viewLink,
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

<?= $this->render('/planned-expenses/index', [
    'searchModel' => $plannedExpenseSearchModel,
    'dataProvider' => $plannedExpenseDataProvider,
    'project' => $model,
]);
?>

<?php if($model->allowsComments): ?>
    <?= $this->render('/project-comments/index', [
        'searchModel' => $commentSearchModel,
        'dataProvider' => $commentDataProvider,
        'project' => $model,
    ]);
    ?>
<?php endif ?>

<?php if(!$model->isDraft): ?>
    <?= $this->render('/statements/project-related-expenses', [
        'searchModel' => $postingSearchModel,
        'postingDataProvider' => $postingDataProvider,
        'project' => $model,
    ]);
    ?>
<?php endif ?>

<?php if ($model->organizationalUnit->hasLoggedInUser()): ?>
    <hr />
    <?= Html::a(Yii::t('app', 'Clone Project') . ' ' . Yii::t('app', '(you will be asked to confirm)') , ['clone-confirm', 'id' => $model->id], [
        //'class' => 'btn btn-info',
    ]) ?>
<?php endif ?>

<?php if(Yii::$app->user->hasAuthorizationFor('projects-management')): ?>

    <?php if(Yii::$app->user->hasAuthorizationFor('office-transactions/create')): ?>
        <?=Html::a(Yii::t('app', 'Create Advance Payment Transaction'), ['office-transactions/create', 'organizational_unit_id'=>$model->organizational_unit_id, 'project_id'=>$model->id, 'type'=>'AP']) ?><br>
        <?php /*if ($amountToBeReimbursed > 0): ?>
            <?=Html::a(Yii::t('app', 'Create Reimbursement Transaction'), ['office-transactions/create', 'organizational_unit_id'=>$model->organizational_unit_id, 'project_id'=>$model->id, 'type'=>'R', 'amount'=>$amountToBeReimbursed]) ?><br>
        <?php endif */ ?>
        <?=Html::a(Yii::t('app', 'Create Direct Payment Transaction'), ['office-transactions/create', 'organizational_unit_id'=>$model->organizational_unit_id, 'project_id'=>$model->id, 'type'=>'DP']) ?><br>
    <?php endif ?>

    <hr>
    <?php $url=Url::toRoute(['projects-management/view', 'id'=>$model->id, 'template'=>'copyandpaste']); ?>
    <?= Html::a(Yii::t('app', 'Copy and Paste View'), $url, ['target'=>'_blank']) ?>
    <?php /*
    <br />
    <?= Html::a(Yii::t('app', 'Copy and Paste View (popup)'), $url, ['target'=>'popup', 'onclick'=>'window.open("' . $url . '","popup","width=600,height=600"); return false;']) ?>
    */ ?>
    
<?php endif ?>
