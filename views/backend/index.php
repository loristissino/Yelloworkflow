<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
?>
<h1><?= Yii::t('app', 'Back End') ?></h1>

<div style="font-size: 1.5em">
<p><?= Html::a(Yii::t('app', 'Accounts'), ['accounts/index']) ?></p>
<p><?= Html::a(Yii::t('app', 'Expense Types'), ['expense-types/index']) ?></p>
<p><?= Html::a(Yii::t('app', 'Transaction Templates'), ['transaction-templates/index']) ?></p>
<p><?= Html::a(Yii::t('app', 'Notification Templates'), ['notification-templates/index']) ?></p>
</div>
