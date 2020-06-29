<?php

use yii\helpers\Html;
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
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Projects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);


$plannedExpenseSearchModel = new PlannedExpenseSearch();
$plannedExpenseDataProvider = $plannedExpenseSearchModel->search(Yii::$app->request->queryParams, PlannedExpense::find()->ofProject($model));

if ($currentStatus!='ProjectWorkflow/draft') {
    
    $commentSearchModel = new ProjectCommentSearch();
    $commentDataProvider = $commentSearchModel->search(Yii::$app->request->queryParams, ProjectComment::find()->forProject($model));
    $commentDataProvider->sort->defaultOrder = ['created_at' => SORT_DESC];
    
    $postingSearchModel = new PostingSearch();
    $postingDataProvider = $postingSearchModel->search(Yii::$app->request->queryParams, Posting::find()->joinWith('transaction')->withRealAccount(false)->relatedToProject($model));
    //$postingDataProvider->sort->defaultOrder = ['date' => SORT_ASC];

}



?>
<div class="project-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= $currentStatus=='ProjectWorkflow/draft' ? Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) : '' ?>

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
            'co_hosts:ntext',
            'partners:ntext',
            'period',
            'place',
            'wf_status',
            [
                'label' => Yii::t('app', 'Organizational Unit'),
                'format' => 'raw',
                'value' => $model->organizationalUnit->viewLink,
            ],
            'created_at:datetime',
            'updated_at:datetime',
            [
                'label' => 'Last Logged Activity At',
                'format' => 'datetime',
                'value' => $model->lastLoggedActivityTime,
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

<?php if($currentStatus!='ProjectWorkflow/draft'): ?>
    <?= $this->render('/project-comments/index', [
        'searchModel' => $commentSearchModel,
        'dataProvider' => $commentDataProvider,
        'project' => $model,
    ]);
    ?>
    <?= $this->render('/statements/project-related-expenses', [
        'searchModel' => $postingSearchModel,
        'postingDataProvider' => $postingDataProvider,
        'project' => $model,
    ]);
    ?>
<?php endif ?>

<?php if ($model->organizationalUnit->hasLoggedInUser()): ?>
    <hr />
    <?= Html::a(Yii::t('app', 'Clone Project'), ['clone', 'id' => $model->id], [
        'class' => 'btn btn-info',
        'data' => [
            'method' => 'post',
        ],
    ]) ?>
<?php endif ?>
