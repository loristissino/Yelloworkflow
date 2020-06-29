<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Posting */

$this->title = Yii::t('app', 'Create Posting');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Postings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="posting-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
