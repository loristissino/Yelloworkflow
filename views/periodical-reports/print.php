<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use app\models\Transaction;
use app\models\TransactionSearch;
use app\models\PeriodicalReportComment;
use app\models\PeriodicalReportCommentSearch;


/* @var $this yii\web\View */
/* @var $model app\models\PeriodicalReport */

$transitions = $model->getAuthorizedTransitions();
$currentStatus = $model->getWorkflowStatus()->getId();

$transactionSearchModel = new TransactionSearch();
$transactionDataProvider = $transactionSearchModel->search(Yii::$app->request->queryParams,
    Transaction::find()->active()->ofPeriodicalReport($model)
);

$transactionDataProvider->sort->defaultOrder = ['date' => SORT_ASC];

$this->title = sprintf('%s %s', $model->name, $model->organizationalUnit);

\yii\web\YiiAsset::register($this);

$attributes = [
        [
            'label'=>Yii::t('app', 'Organizational Unit'),
            'format'=>'raw',
            'value'=>$model->organizationalUnit,
        ],
        [
            'label'=>Yii::t('app', 'Period'),
            'format'=>'raw',
            'value'=>sprintf('%s - %s', Yii::$app->formatter->asDate($model->begin_date), Yii::$app->formatter->asDate($model->end_date)),
        ],
        'wf_status',
];

?>

<div class="periodical-report-view">

    <h1><?= Html::encode($model->name) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $attributes
    ]) ?>

</div>

<h2><?= Yii::t('app', 'Balance') ?></h2>
<p><?= Yii::t('app', 'Only submitted and recorded transactions are taken into consideration.') ?></p>
<?php if ($beginningBalanceDataProvider->getTotalCount() > 0): ?>
    <h3><?= Yii::t('app', 'Beginning of Period') ?></h3>
    <?= $this->render('/statements/_balance-grid', [
            'dataProvider' => $beginningBalanceDataProvider,
    ]);
    ?> 
<?php endif ?>
<h3><?= Yii::t('app', 'End of Period') ?></h3>
<?= $this->render('/statements/_balance-grid', [
        'dataProvider' => $endBalanceDataProvider,
]);
?> 


<?= $this->render('/transactions/print-index', [
    'searchModel' => $transactionSearchModel,
    'dataProvider' => $transactionDataProvider,
    'periodicalReport' => $model,
]); 
?>

