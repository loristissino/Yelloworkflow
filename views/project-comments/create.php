<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProjectComment */

$this->title = Yii::t('app', 'Create Project Comment');
$this->params['breadcrumbs'][] = ['label' => $project->title, 'url' => [$controller . '/view', 'id'=>$project->id]];
$this->params['breadcrumbs'][] = $this->title;

if ($reply_to and !$model->comment) {
    $model->comment = '> ' . $reply_to->comment . "\n";
} 

?>
<div class="project-comment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
