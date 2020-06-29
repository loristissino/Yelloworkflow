<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Authorization */

$this->title = Yii::t('app', 'Create Authorization');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Authorizations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authorization-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
