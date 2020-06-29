<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Affiliation */

$this->title = Yii::t('app', 'Create Affiliation');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Affiliations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="affiliation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
