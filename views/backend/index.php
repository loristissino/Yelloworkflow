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
<p><?= Html::a(Yii::t('app', 'Vendors'), ['vendors/index']) ?></p>
<p><?= Html::a(Yii::t('app', 'Attachments'), ['attachments', 'pagesize'=>666, 'sort'=>'-id']) ?></p>

<hr />

<p><?= Html::a(Yii::t('app', 'Markdown Documentation'), ['markdown-documentation']) ?></p>

<hr />

<p><?= Html::a(Yii::t('app', 'Project Workflow'), ['project-workflow', 'seed'=>3]) ?></p>
<p><?= Html::a(Yii::t('app', 'Periodical Report Workflow'), ['periodical-report-workflow', 'seed'=>3]) ?></p>
<p><?= Html::a(Yii::t('app', 'Transaction Workflow'), ['transaction-workflow', 'seed'=>3]) ?></p>

</div>
