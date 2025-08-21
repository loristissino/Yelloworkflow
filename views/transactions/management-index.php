<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/*
 */
$this->title = Yii::t('app', 'Transactions');
//$this->params['breadcrumbs'][] = $this->title;

$this->registerJs(
    "
    let view = localStorage.getItem('prmi_view') || 'standard';
    
    if (view == 'standard') {
        $('#with_attachments').hide();
        $('#standard_view').show();
    }
    else {
        $('#with_attachments').show();
        $('#standard_view').hide();
    }

    let rotations = {};
    $('.inline_image').click(function(ev) {
        if (!rotations[ev.target.id]) {
            rotations[ev.target.id] = {degrees: 0, width: ev.target.width, height: ev.target.height};
        }
        rotations[ev.target.id].degrees+=90;
        ev.target.style.transform = 'rotate(' + rotations[ev.target.id].degrees + 'deg)';
    });
    
    (function ($) {
        $('#toggle_view').click(function() {
            $('#with_attachments').toggle();
            $('#standard_view').toggle();
            if (view == 'standard') {
                view = 'with_attachments';
            }
            else {
                view = 'standard';
            }
            localStorage.setItem('prmi_view', view);
        });
    })(window.jQuery);
    ",
    \yii\web\View::POS_END,
    'transactions_view_js_code_end'
);

?>

<div class="transaction-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <?php if (($dataProvider->count > 0)): ?>

    <div style="margin-bottom: 20px"><a href="#" id="toggle_view"><?= Yii::t('app', 'Toggle View') ?></a></div>

    <div id="with_attachments">

        <?php foreach($dataProvider->getModels() as $data): ?>
            
            <div>
                <div>
                    <?= Yii::$app->formatter->asDate($data['date']) ?> - <?= $data->workflowLabel ?>
                    <?= Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['transactions-management/view', 'id'=>$data->id], [
                                'title'=>Yii::t('app', 'View'),
                                ])
                    ?>
                </div>
                <div style="font-style: italic; font-weight: bold"><?= $data['description'] ?></div>
                <div style=""><?= $data['notes'] ?></div>
                <div style="padding-left: 40px">
                    <div><?= $data->postingsViewWithoutLink ?></div>
                    <?php foreach ($data->files as $file): ?>
                        <?php if(in_array(strtolower($file->type), ['jpeg', 'jpg', 'gif', 'png'])): ?>
                            <img class="inline_image" id="<?= $file->id ?>" style="width: 60%; height: auto; margin-top: 20px;" src="<?= $file->url ?>">
                        <?php else: ?>
                            <?= Html::a($file->name . '.' . $file->type, $file->url, ['target'=>'_blank']) ?>   
                        <?php endif ?>
                    <?php endforeach ?>
                </div>
                <hr />
            </div>
            
        <?php endforeach ?>
    
    </div>
    <div id="standard_view">

        <?=Html::beginForm(['process'],'post');?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],
                ['class' => 'yii\grid\CheckboxColumn'],

                // 'id',
                // 'periodical_report_id',
                [
                    'label'=>Yii::t('app', 'Date'),
                    'attribute'=>'date',
                    'format'=>'raw',
                    'value'=>function($data) {
                        return Yii::$app->formatter->asDate($data['date']);
                    },
                ],
                'description',
                /*
                [
                    'attribute' => 'project_id',
                    'format' => 'raw',
                    'value' => 'project.title',
                ],
                */
                [
                    'label' => 'Postings',
                    'format' => 'raw',
                    'value' => 'postingsViewWithoutLink',
                ],
                [
                    'attribute'=>'wf_status',
                    'format'=>'raw',
                    'value'=>'workflowLabel',
                ],

                ['class' => 'yii\grid\ActionColumn',
                    'template'=>'{view} {workflow}',
                    'buttons'=>[
                        'view' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['transactions-management/view', 'id'=>$model->id], [
                                'title'=>Yii::t('app', 'View'),
                                ]);
                            },
                        'workflow' => function ($url, $model) {
                            if (Yii::$app->user->hasAuthorizationFor('workflow')) {
                                $icon = 'glyphicon glyphicon-calendar';
                                return Html::a(sprintf('<span class="%s" style="color:#800080" title="%s"></span>', $icon, Yii::t('app', 'Edit Workflow Status')), ['workflow/update', 'type'=>get_class($model), 'id'=>$model->id, 'return'=>Url::current()]);
                                }
                            },
                    ],
                    'contentOptions'=>['style'=> 'width: 80px'],
                ]
            ],
        ]); ?>
        
        <?php if ($periodicalReport && $periodicalReport->isSubmitted): ?>
            <?= Yii::t('app', 'With the selected transactions: ') ?>
            <?= Html::a(Yii::t('app', 'Set Recorded'), ['transactions-management/process', 'action'=>'setRecorded', 'redirect'=>\yii\helpers\Url::toRoute(['periodical-reports-management/view', 'id'=>$periodicalReport->id])], ['data-method'=>'post'])?>
        <?php endif ?>
        
        <?= Html::endForm();?>
    
    </div>

    <?php else: ?>

    <p><?= Yii::t('app', 'No transactions.') ?></p>

    <?php endif ?>

</div>
