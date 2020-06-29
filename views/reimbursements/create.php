<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Reimbursement */

$this->title = Yii::t('app', 'Create Reimbursement');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reimbursements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reimbursement-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
