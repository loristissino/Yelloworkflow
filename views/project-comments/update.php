<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProjectComment */

$this->title = Yii::t('app', 'Update Project Comment: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => [$controller . '/index']];
$this->params['breadcrumbs'][] = ['label' => $project->title, 'url' => [$controller . '/view', 'id'=>$project->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="project-comment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
