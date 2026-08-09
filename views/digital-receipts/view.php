<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use app\models\DigitalReceipt;
use yii\bootstrap\Alert;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $model app\models\DigitalReceipt */

$this->registerJs(
    "
    $(document).ready(function() {
        var \$iframe = $('#receipt');
        
        function resizeIframe() {
            let iframeHeight = \$iframe.contents().find('html').height();
            console.log('Resizing iframe to height:', iframeHeight);
            \$iframe.css('height', iframeHeight + 'px');
        }
        
        // Trigger on load
        \$iframe.on('load', function() {
            console.log('Iframe loaded');
            resizeIframe();
        });
        
        // Also trigger immediately in case iframe is already loaded
        if (\$iframe[0].contentWindow.document.readyState === 'complete') {
            console.log('Iframe already loaded');
            resizeIframe();
        }
    });    
    ",
    \yii\web\View::POS_END,
    'iframe_manager'
);

$this->title = $model->title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Digital Receipts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$transitions = $model->getAuthorizedTransitions();
$currentStatus = $model->getWorkflowStatus()->getId();

$shownAttributes = [
            [
                'attribute'=>'sequential_number',
                'format'=>'raw',
                'value' => function ($model) {
                    $html = $model->completeSequentialNumber;
                    if ($model->sequential_number) {
                        $html .= '&nbsp;&nbsp;&nbsp;' . Html::a('<span class="glyphicon glyphicon-list-alt"></span', ['/site/digital-receipt', 'id'=>$model->client_id], ['style'=>'text-decoration: none', 'title'=>Yii::t('app', 'View')]);
                        $html .= '&nbsp;' . Html::a('<span class="glyphicon glyphicon-print"></span', ['/site/digital-receipt', 'id'=>$model->client_id, 'format'=>'pdf'], ['style'=>'text-decoration: none', 'title'=>Yii::t('app', 'PDF / Print')]);
                    }
                    return $html;
                },
            ],
            'date',
            'wf_status',
            [
                'attribute' => 'user',
                'label'=>Yii::t('app', 'User'),
            ],
            [
                'attribute' => 'organizationalUnit',
                'label'=>Yii::t('app', 'Organizational Unit'),
            ],
            [
                'label' => Yii::t('app', 'Document Type Description'),
                'value' => $model->documentLabel,
            ],
            'total_amount',
            'email:email',
            'phone',
            'document_number',
            'cash_payment_amount',
            [
                'attribute' => 'expo_id',
                'format'=>'raw',
                'value' => function($model){
                    $html = $model->expo;
                    if ($model->expo_id) {
                        $html .= ' ' . Html::a('⛓️‍💥', ['unlink-from-expo', 'id'=>$model->id], ['class'=>'btn', 'data-confirm'=>Yii::t('app', 'Do you really want to unlink this receipt from the Expo?'), 'data-method'=>'POST', 'title'=>Yii::t('app', 'Unlink from Expo')]);
                    }
                    return $html;
                }
            ],
            'electronic_payment_amount',
            [
                'label' => Yii::t('app', 'Activities'),
                'format' => 'raw',
                'value' => Html::a(Yii::t('app', 'Workflow Log'), ['log', 'id'=>$model->id]),
            ],

];

if (Yii::$app->session->get('debug')) {
    $extraAttributes = 
        [
            'created_at:datetime',
            'updated_at:datetime',
            'sent_at:datetime',
            'processed_at:datetime',
            'voided_at:datetime',
            'transaction_id',
            'client_id',
            'assigned_id',
            'tags',
            [
                'attribute' => 'voiding_receipt_assigned_id',
                'format'=>'raw',
                'value' => function ($model) {
                    if ($model->voiding_receipt_assigned_id){
                        return Html::a($model->voiding_receipt_assigned_id, ['view', 'id'=>DigitalReceipt::find()->withAssignedId($model->voiding_receipt_assigned_id)->one()->id]);
                    }
                    return null;
                },
            ],
            [
                'attribute' => 'voided_receipt_assigned_id',
                'format'=>'raw',
                'value' => function ($model) {
                    if ($model->voided_receipt_assigned_id){
                        return Html::a($model->voided_receipt_assigned_id, ['view', 'id'=>DigitalReceipt::find()->withAssignedId($model->voided_receipt_assigned_id)->one()->id]);
                    }
                    return null;
                },
            ],
            [
                'attribute' => 'parent_id',
                'format'=>'raw',
                'value' => function ($model) {
                    if ($model->parent_id){
                        $parent = $model->parentReceipt;
                        return Html::a($model->parentReceipt->assigned_id, ['view', 'id'=>$model->parentReceipt->id]);
                    }
                    return null;
                },
            ],
            [
                'label' => 'Children',
                'format'=>'raw',
                'value' => function ($model) {
                    $children = $model->getChildrenDigitalReceipts()->select(['id','assigned_id'])->asArray()->all();
                    if (sizeof($children)==0){
                        return null;
                    }
                    $links = [];
                    foreach($children as $child) {
                        $links[] = Html::a($child['assigned_id'], ['view', 'id'=>$child['id']]);
                    }
                    return join(', ', $links);
                },
            ],
            [
                'attribute' => 'api_request',
                'format'=>'raw',
                'value' => function ($model) {
                    return $model->getJsonFieldAsHTML('api_request');
                },
            ],
            [
                'attribute' => 'api_response',
                'format'=>'raw',
                'value' => function ($model) {
                    return $model->getJsonFieldAsHTML('api_response');
                },
            ],
            [
                'attribute' => 'api_callback',
                'format'=>'raw',
                'value' => function ($model) {
                    return $model->getJsonFieldAsHTML('api_callback');
                },
            ],
            [
                'attribute' => 'notes',
                'format'=>'raw',
                'value' => function ($model) {
                    return $model->getJsonFieldAsHTML('notes');
                },
            ],
            [
                'attribute' => 'issuer_data',
                'format'=>'raw',
                'value' => function ($model) {
                    return $model->getJsonFieldAsHTML('issuer_data');
                },
            ],
        ];
    array_push($shownAttributes, ...$extraAttributes);

}

?>
<div class="digital-receipt-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div style="text-align: left">
        <iframe id="receipt" scrolling="no" style="width:480px; height:auto; border:none" src="<?=Url::to(['site/digital-receipt', 'id'=>$model->client_id, 'framed'=>1])?>"></iframe>
    </div>

    <p>

    <?php if (!$model->hasBeenIssued): ?>
        <?= Alert::widget([
            'options' => ['class' => 'alert-danger'],
            'body' => Yii::t('app', 'Something went wrong when trying to issue the receipt. See details below.'),
        ])?>
    <?php else: ?>
        <?= $this->render('/workflow/_workflowbuttons', [
            'model' => $model,
            'transitions' => $transitions
        ]) ?>
        
    <?php endif ?>
    
    <?php if (Yii::$app->session->get('debug', false) && $model->canHavePDFFetched && $model->hasBeenIssued && !$model->hasPDF): ?>
        <?= Html::a(Yii::t('app', 'Fetch PDF'), Url::to(['fetch-pdf', 'id' => $model->id]), [
            'class' => 'btn btn-info',
            'style' => 'background-color: #FFC0CB; border-color: #FFC0CB',
            //'data' => ['method'=>'GET'],
        ]) ?> 
    <?php endif ?>

    <?php if($model->canHaveReturns): ?>

        <?php if($model->hasBeenProcessed && $model->canHaveReturns): ?>
            <?= Html::a(Yii::t('app', 'Process a Return'), Url::to(['process-return', 'id' => $model->id]), [
                'class' => 'btn btn-info',
                'style' => 'background-color: #800080; border-color: #800080',
                //'data' => ['method'=>'GET'],
            ]) ?> 
        <?php elseif ($model->canBeProcessed): ?>
            <?= Html::a(Yii::t('app', 'Process Sale'), Url::to(['process-sale', 'id' => $model->id]), [
                'class' => 'btn btn-info',
                'style' => 'background-color: #ADD8E6; border-color: #ADD8E6',
                //'data' => ['method'=>'GET'],
            ]) ?> 
        <?php endif ?>

    <?php endif ?>

    <?php if ($model->getWorkflowStatus()->getId()=='DigitalReceiptWorkflow/issued'): ?>
        <p>
            <?= Yii::t('app', 'This receipt will be sent to <b>{contact}</b>.', ['contact' => $model->likelyContact]) ?> -
            <?= Html::a(Yii::t('app', 'Edit'), ['update', 'id'=>$model->id]) ?>
        </p>
    <?php endif ?>



    </p>
    
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $shownAttributes,
    ]) ?>
    
    <?php if(Yii::$app->session->get('debug', false)): ?>

        <?php if ($model->files): ?>
            <h2><?= Yii::t('app', 'Attachments') ?></h2>
             <?= \nemmo\attachments\components\AttachmentsTable::widget(['model' => $model, 'showDeleteButton'=>false]) ?>
            <hr>
        <?php endif ?>
        
        <?= Html::a(Yii::t('app', 'Check items'), Url::to(['check-items', 'id' => $model->id]), [
            'class' => 'btn btn-info',
            'style' => 'background-color: green; border-color: green',
            //'data' => ['method'=>'GET'],
        ]) ?> 

        <?= Html::a(Yii::t('app', 'Fetch and Show Data'), Url::to(['fetch-data', 'id' => $model->id]), [
            'class' => 'btn btn-info',
            'style' => 'background-color: #FFA500; border-color: #FFA500',
            //'data' => ['method'=>'GET'],
        ]) ?> 
        
    <?php endif ?>

</div>
