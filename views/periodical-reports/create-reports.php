<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PeriodicalReport */

$this->title = Yii::t('app', 'Create Periodical Reports');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Periodical Reports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="periodical-report-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_bulk_creator_form', [
        'model' => $model,
    ]) ?>

</div>
