<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use app\models\Transaction;
use app\models\TransactionSearch;


/* @var $this yii\web\View */
/* @var $model app\models\PeriodicalReport */

$transitions = $model->getAuthorizedTransitions();
$currentStatus = $model->getWorkflowStatus()->getId();

$transactionSearchModel = new TransactionSearch();
$transactionDataProvider = $transactionSearchModel->search(Yii::$app->request->queryParams,
    Transaction::find()->active()->ofPeriodicalReport($model)
);

$transactionDataProvider->sort->defaultOrder = ['date' => SORT_ASC];

$salesViews = [
    'lines'=>'Line-based report', 
    'pivot'=>'Pivot-based report'
];

$salesLinks = [];
foreach($salesViews as $view=>$description) {
    $salesLinks[] = Html::a(Yii::t('app', $description), ['statements/sales', 'id'=>$model->id, 'view'=>$view]);
}

$this->title = sprintf('%s, %s', $model->name, $model->organizationalUnit);

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Periodical Reports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$transactionsView = Yii::$app->controller->id == 'periodical-reports-management' ? 'management-index' : 'submissions-index';

?>

<div class="periodical-report-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>

    <?= $this->render('/workflow/_workflowbuttons', [
        'model' => $model,
        'transitions' => $transitions
    ]) ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
        /*
            [
                'label' => 'Organizational Unit',
                'format' => 'raw',
                'value' => $model->organizationalUnit->viewLink,
            ],*/
            'name',
            'begin_date',
            'end_date',
            'wf_status',
            [
                'label' => Yii::t('app', 'Sales'),
                'format' => 'raw',
                'value' => implode(' - ', $salesLinks),
            ],
            // 'created_at:datetime',
            // 'updated_at:datetime',
        ],
    ]) ?>

</div>

<?= $this->render('/transactions/'.$transactionsView, [
    'searchModel' => $transactionSearchModel,
    'dataProvider' => $transactionDataProvider,
    'periodicalReport' => $model,
]); 
?>

