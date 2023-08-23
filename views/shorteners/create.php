<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Shortener */

$this->title = Yii::t('app', 'Create Shortener');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shorteners'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shortener-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
