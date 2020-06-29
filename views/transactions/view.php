<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use app\models\Posting;
use app\models\PostingSearch;

use app\assets\TransactionFormAsset;

TransactionFormAsset::register($this);

$vat_number_messages = [
    'ok' => Yii::t('app', 'VAT Number seems to be OK.'),
    'wrong' => Yii::t('app', 'VAT Number seems to be wrong.'),
];

$this->registerJs(
    "
    let templates = [];
    let vat_number_messages = JSON.parse('".\yii\helpers\Json::htmlEncode($vat_number_messages)."');
    (function ($) {
        let vn = $('#vat_number');
    
        let number = vn.html();
        
        if (!number) {
            return;
        }
                    
        let valid = checkVATNumber(number);
        if (valid)
            vn.removeClass('bad').addClass('ok').attr('title', vat_number_messages.ok);
        else
            vn.removeClass('ok').addClass('bad').attr('title', vat_number_messages.wrong);
        
    })(window.jQuery);
    ",
    \yii\web\View::POS_END,
    'transaction_js_code_end'
);

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */

$transitions = $model->getAuthorizedTransitions();
$currentStatus = $model->getWorkflowStatus()->getId();
$is_draft = $currentStatus == 'TransactionWorkflow/draft';
$is_prepared = $currentStatus == 'TransactionWorkflow/prepared';

$postingSearchModel = new PostingSearch();
$postingDataProvider = $postingSearchModel->search(Yii::$app->request->queryParams,
    Posting::find()->ofTransaction($model)
);

$postingDataProvider->sort->defaultOrder = ['amount' => SORT_DESC];

$this->title = $model->description;

$template_title_property = 'title';
$template_description_property = 'description';

if (Yii::$app->controller->id == 'transaction-submissions') {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Periodical Reports'), 'url' => ['periodical-report-submissions/index']];
    $this->params['breadcrumbs'][] = ['label' => $model->periodicalReport, 'url' => ['periodical-report-submissions/view', 'id'=>$model->periodical_report_id]];
    $projectViewLink = 'submitterViewLink';
}
elseif (Yii::$app->controller->id == 'transactions-management') {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Periodical Reports'), 'url' => ['periodical-reports-management/index']];
    $this->params['breadcrumbs'][] = ['label' => sprintf('%s (%s)', $model->periodicalReport, $model->organizationalUnit), 'url' => ['periodical-reports-management/view', 'id'=>$model->periodical_report_id]];
    $projectViewLink = 'viewLink';
}
else {  // office
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Office Transactions'), 'url' => ['office-transactions/index']];
    $projectViewLink = 'viewLink';
    $template_title_property = 'o_title';
    $template_description_property = 'o_description';
}

$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="transaction-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= $is_draft ? Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) : null ?>
        <?= $is_draft || ($is_prepared && Yii::$app->user->id == $model->user_id) ? Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) : null ?>
        <?= $this->render('/workflow/_workflowbuttons', [
            'model' => $model,
            'transitions' => $transitions
        ]) ?>
        <span class="advanced_view">
        <?= $is_draft ? Html::a(Yii::t('app', 'Invert'), ['invert', 'id' => $model->id], [
            'class' => 'btn btn-info',
            'style' => 'background-color: yellow; border-color: #FFA500; color: #222222',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to invert the transaction?'),
                'method' => 'post',
            ],
        ]) : null ?>
        </span>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => Yii::t('app', 'Date'),
                'format' => 'date',
                'value' => $model->date,
            ],
            'description',
            'notes',
            [
                'label' => Yii::t('app', 'Template'),
                'format' => 'raw',
                'value' => Html::tag('div', Html::tag('div', 
                    $model->transactionTemplate->$template_title_property) . Html::tag('em', $model->transactionTemplate->$template_description_property)
                ),
            ],
        ],
    ]) ?>

</div>

<?= $this->render('/postings/index', [
    'searchModel' => $postingSearchModel,
    'dataProvider' => $postingDataProvider,
    'transaction' => $model,
]); 
?>

<div class="attachments-view">
    <h2><?= Yii::t('app', 'Attachments') ?></h2>
    <?= \nemmo\attachments\components\AttachmentsTable::widget(['model' => $model, 'showDeleteButton'=>$is_draft]) ?>
</div>

<div class="transaction-view">

    <h2><?= Yii::t('app', 'Details') ?></h2>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'label' => Yii::t('app', 'Periodical Report'),
                'format' => 'raw',
                'value' => $model->periodicalReport->viewLink,
            ],
            [
                'label' => Yii::t('app', 'Project'),
                'format' => 'raw',
                'value' => $model->project ? $model->project->$projectViewLink : null,
            ],
            //'event_id',
            'vendor',
            [
                'attribute' => 'vat_number',
                'format' => 'raw',
                'value' => Html::tag('span', Html::encode($model->vat_number), ['id'=>'vat_number', 'class'=>'vat_number']),
            ],
            'invoice',
            'wf_status',
            [
                'label' => 'Last Updater',
                'format' => 'raw',
                'value' => $model->user,
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>

<div><a href="#" id="toggle_advanced_view"><?= Yii::t('app', 'Toggle Advanced View') ?></a></div>
