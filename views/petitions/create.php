<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Petition */

$this->title = Yii::t('app', 'Create Petition');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Petitions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="petition-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
