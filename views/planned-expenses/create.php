<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PlannedExpense */

$this->title = Yii::t('app', 'Create Planned Expense');
$this->params['breadcrumbs'][] = ['label' => $project->title, 'url' => ['project-submissions/view', 'id'=>$project->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="planned-expense-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
