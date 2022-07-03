<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PlannedExpense */


$this->title = Yii::t('app', 'Update Planned Expense: {name}', [
    'name' => $model->description,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Projects'), 'url' => ['project-submissions/index']];
$this->params['breadcrumbs'][] = ['label' => $model->project->title, 'url' => ['project-submissions/view', 'id'=>$model->project->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Planned Expenses');
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="planned-expense-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
