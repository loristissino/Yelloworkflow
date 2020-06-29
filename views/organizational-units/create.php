<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrganizationalUnit */

$this->title = Yii::t('app', 'Create Organizational Unit');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Organizational Units'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organizational-unit-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
