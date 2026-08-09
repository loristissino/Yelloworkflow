<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Shortener */

$this->title = Yii::t('app', $model->multiline ? 'Create Shortenings': 'Create Shortening');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shortenings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shortener-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    
    <hr>

    <?php if($model->multiline): ?>
        <p><?= Html::a(Yii::t('app', 'Single URL'), ['create']) ?></p>
    <?php else: ?>
        <p><?= Html::a(Yii::t('app', 'Multiline'), ['create', 'multiline'=>1]) ?></p>
    <?php endif ?>

</div>
