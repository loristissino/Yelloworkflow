<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Transaction;
use app\models\TransactionSearch;

/* @var $this yii\web\View */
/* @var $model app\models\OrganizationalUnit */

$this->title = Yii::t('app', 'Transactions');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Organizational Units'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['organizational-units/view', 'id'=>$model->id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="transactions-view">
    
    <h1><?= Html::encode($model->name) ?></h1>

    <?= $this->render('/transactions/management-index', [
        'searchModel' => $transactionSearchModel,
        'dataProvider' => $transactionDataProvider,
        'periodicalReport' => null, //$model,
    ]);
    ?>

</div>
