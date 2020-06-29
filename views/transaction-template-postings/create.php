<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TransactionTemplatePosting */

$this->title = Yii::t('app', 'Create Transaction Template Posting');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Back End'), 'url'=>['backend/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transaction Templates'), 'url' => ['transaction-templates/index']];
$this->params['breadcrumbs'][] = ['label' => $template->title, 'url' => ['transaction-templates/view', 'id'=>$template->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-template-posting-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
