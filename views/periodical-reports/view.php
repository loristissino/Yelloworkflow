<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use app\models\Transaction;
use app\models\TransactionSearch;
use app\models\PeriodicalReportComment;
use app\models\PeriodicalReportForm;
use app\models\PeriodicalReportCommentSearch;
use nemmo\attachments\components\AttachmentsInput;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PeriodicalReport */

$transitions = $model->getAuthorizedTransitions();
$currentStatus = $model->getWorkflowStatus()->getId();

$is_draft = $currentStatus == 'PeriodicalReportWorkflow/draft';

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

if ($currentStatus!='PeriodicalReportWorkflow/draft') {
    
    $commentSearchModel = new PeriodicalReportCommentSearch();
    $commentDataProvider = $commentSearchModel->search(Yii::$app->request->queryParams, PeriodicalReportComment::find()->forPeriodicalReport($model));
    $commentDataProvider->sort->defaultOrder = ['created_at' => SORT_DESC];

}

$attributes = [
        'name',
        [
            'label'=>Yii::t('app', 'Begin Date'),
            'attribute'=>'begin_date',
            'format'=>'raw',
            'value'=>Yii::$app->formatter->asDate($model->begin_date),
        ],
        [
            'label'=>Yii::t('app', 'End Date'),
            'attribute'=>'end_date',
            'format'=>'raw',
            'value'=>Yii::$app->formatter->asDate($model->end_date),
        ],
        'wf_status',
        [
            'label' => Yii::t('app', 'Sales'),
            'format' => 'raw',
            'value' => implode(' - ', $salesLinks),
        ],
];

if ($transactionsView == 'management-index') {
    $attributes[] =
        [
            'label' => Yii::t('app', 'Recap'),
            'format' => 'raw',
            'value' => Html::a(Yii::t('app', 'Balance'), ['statements/balance', 'id'=>$model->id]),
        ];
}

if ($transactionsView == 'submissions-index') {
    $attributes[] =
        [
            'label' => Yii::t('app', 'Versions'),
            'format' => 'raw',
            'value' => Html::a(Yii::t('app', 'Printable Version'), ['print', 'id'=>$model->id], ['target'=>'_blank']),
        ];
}


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
        'attributes' => $attributes
    ]) ?>

</div>

<?= $this->render('/transactions/'.$transactionsView, [
    'searchModel' => $transactionSearchModel,
    'dataProvider' => $transactionDataProvider,
    'periodicalReport' => $model,
]); 
?>

<?php if($currentStatus!='PeriodicalReportWorkflow/draft'): ?>
    <?= $this->render('/periodical-report-comments/index', [
        'searchModel' => $commentSearchModel,
        'dataProvider' => $commentDataProvider,
        'periodicalReport' => $model,
    ]);
    ?>
<?php endif ?>

<div class="attachments-view">
    <h2><?= Yii::t('app', 'Attachments') ?></h2>
    <?= \nemmo\attachments\components\AttachmentsTable::widget(['model' => $model, 'showDeleteButton'=>$is_draft]) ?>
</div>

<?php if($is_draft): ?>
    <div class="periodical-report-form">
        <?php $form = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data'],
            'action' => ['periodical-report-submissions/update', 'id'=>$model->id]
            ]); ?>

        <?= $form->field($model, 'id')->hiddenInput(['maxlength' => true])->label('') ?>
        
        <fieldset id="attachments_fieldset">
            <?= AttachmentsInput::widget([
                // see https://github.com/Nemmo/yii2-attachments
                'id' => 'file-input', // Optional
                'model' => $model,
                'options' => [ // Options of the Kartik's FileInput widget
                    'multiple' => true, // If you want to allow multiple upload, default to false
                ],
                'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget 
                    // see https://plugins.krajee.com/file-basic-usage-demo
                    'maxFileCount' => 3, // Client max files
                    'initialPreview' => $model->isNewRecord ? [] : $model->getInitialPreview(),
                    'dropZoneEnabled' => true,
                    'showPreview' => false,
                    'allowedFileExtensions' => ['png', 'pdf', 'jpeg', 'jpg'],
                ]
            ]) ?>
        </fieldset>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success', 'id'=>'save_button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
        
    </div>
<?php endif ?>
