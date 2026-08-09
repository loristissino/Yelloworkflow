<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */

$this->title = Yii::t('app', 'Separate Transaction?');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Periodical Reports'), 'url'=>['periodical-report-submissions/index']];
$this->params['breadcrumbs'][] = ['label' => $model->transaction->periodicalReport, 'url' => ['periodical-report-submissions/view', 'id'=>$model->transaction->periodicalReport->id]];
$this->params['breadcrumbs'][] = ['label' => $model->transaction, 'url' => ['transaction-submissions/view', 'id'=>$model->transaction->id]];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="transaction-update">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <p><?= Yii::t('app', 'Do you really want to create a separate transaction for the receipt <strong>{number}</strong>?', ['number'=>$model->completeSequentialNumber]) ?>
    <br>
    <?= Yii::t('app', 'This is needed only in special cases, such as when a donation must be assigned to a specific project or when a sale should be excluded from an expo.')?>
    </p>
    
    <?= Html::a(Yii::t('app', 'Yes, proceed'), ['journalize-separately', 'receipt'=>$model->id], ['data-method'=>'post', 'class' => 'btn btn-primary'])?>

</div>

