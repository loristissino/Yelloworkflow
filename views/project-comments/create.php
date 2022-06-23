<?php

use yii\helpers\Html;
use app\models\ProjectComment;
use app\models\ProjectCommentSearch;


/* @var $this yii\web\View */
/* @var $model app\models\ProjectComment */

$this->title = Yii::t('app', 'Create Project Comment');
$this->params['breadcrumbs'][] = ['label' => $project->title, 'url' => [$controller . '/view', 'id'=>$project->id]];
$this->params['breadcrumbs'][] = $this->title;

if ($reply_to and !$model->comment) {
    $lines = explode("\n", $reply_to->comment);
    $newlines = [];
    foreach($lines as $line){
        $newlines[] = '> ' . $line;
    }
    $model->comment = join("\n", $newlines);
    if (sizeof($lines)>0) {
        $model->comment .= "\n\n";
    }
    
    //$model->comment = '> ' . $reply_to->comment . "\n";
} 

$commentSearchModel = new ProjectCommentSearch();
$commentDataProvider = $commentSearchModel->search(Yii::$app->request->queryParams, ProjectComment::find()->forProject($project));
$commentDataProvider->sort->defaultOrder = ['created_at' => SORT_DESC];

?>
<div class="project-comment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'project' => $project,
        'controller' => $controller,
    ]) ?>
    
<?= $this->render('/project-comments/index', [
    'searchModel' => $commentSearchModel,
    'dataProvider' => $commentDataProvider,
    'project' => $project,
    'is_thread' => true,
    'controller' => $controller,
]);
?>

</div>
