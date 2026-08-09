<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Expo */

$this->title = Yii::t('app', 'Create Expo');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Expos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
