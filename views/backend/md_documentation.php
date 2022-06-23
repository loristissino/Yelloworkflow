<?php

use app\models\Account;
use app\models\ExpenseType;
use app\models\TransactionTemplate;

?># <?= Yii::$app->name ?>


<?= Yii::t('app', 'Generated on {date}', ['date'=>date('d/m/Y')]) ?>


## <?= Yii::t('app', 'Types of Planned Expenses for Projects') ?>


<?php foreach(ExpenseType::find()->active()->all() as $expenseType): ?>
* **<?= $expenseType->name ?>**
<?php endforeach ?>

## <?= Yii::t('app', 'Available Accounts') ?>


<?php foreach(Account::find()->active()->all() as $account): ?>
* **<?= $account->name ?>**
<?php endforeach ?>


## <?= Yii::t('app', 'Transaction Templates') ?>


<?php foreach(TransactionTemplate::find()->active()->orderBy(['rank' => SORT_ASC, 'title' => SORT_ASC])->all() as $transactionTemplate): ?>
* **<?= $transactionTemplate->title ?>**  
_<?= $transactionTemplate->description ?>_  
<?php foreach ($transactionTemplate->getTransactionTemplatePostings()->orderBy(['rank' => SORT_ASC])->all() as $posting): ?>
  - <?= $posting->account ?> (<?= $posting->viewDc ?>)
<?php endforeach ?>
  

<?php endforeach ?>
