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

    <h2><?= Yii::t('app', 'Description') ?></h2>
    <?= Html::encode($model->description) ?>

    <?php if ($model->co_hosts): ?>
        <h2><?= Yii::t('app', 'Co-hosts') ?></h2>
        <?= Html::encode($model->co_hosts) ?>
    <?php endif ?>

    <?php if ($model->partners): ?>
        <h2><?= Yii::t('app', 'Partners') ?></h2>
        <?= Html::encode($model->partners) ?>
    <?php endif ?>

    <h2><?= Yii::t('app', 'Period') ?></h2>
    <?= Html::encode($model->period) ?>

    <h2><?= Yii::t('app', 'Place') ?></h2>
    <?= Html::encode($model->place) ?>

    <h2><?= Yii::t('app', 'Organizational Unit') ?></h2>
    <?= Html::encode($model->organizationalUnit) ?>
    
    <h2><?= Yii::t('app', 'Planned Expenses') ?></h2>
    <ul>
    <?php foreach($plannedExpenseDataProvider->models as $plannedExpense): ?>
    
        <li><?= Yii::t('app', '<em>{type}</em> - {description}: {amount}', [
            'type' => $plannedExpense->expenseType->name,
            'description' => $plannedExpense->description,
            'amount' => Yii::$app->formatter->asCurrency($plannedExpense->amount)
        ]) ?></li>
    <?php endforeach ?>
    </ul>

</div>
