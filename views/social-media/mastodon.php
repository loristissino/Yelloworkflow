<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Mastodon');
$this->params['breadcrumbs'][] =  ['label' => Yii::t('app', 'Social Media'), 'url' => ['index']];;
$this->params['breadcrumbs'][] = $this->title;

$dataProvider = $mastodon->getScheduledPosts();

?>

<div class="mastodon-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if(sizeof($dataProvider->getModels())>0): ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'id',
                [
                    'label'=>Yii::t('app', 'Scheduled At'),
                    'attribute'=>'scheduled_at',
                    'format'=>'raw',
                    'value'=>function($data) {
                        return Yii::$app->formatter->asDate($data['scheduled_at'], 'php:d/m/Y, H:i');
                    },
                ],
                [
                    'label'=>Yii::t('app', 'Status'),
                    'attribute'=>'text',
                    'format'=>'raw',
                    'value'=>function($data) {
                        return $data['text'];
                    },
                ],
                [
                    'label'=>Yii::t('app', 'Attachments'),
                    'attribute'=>'attachments',
                    'format'=>'raw',
                ],
                'poster',
                ['class' => 'yii\grid\ActionColumn',
                    'template'=>'{delete}',
                    'buttons'=>[
                        'delete' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['social-media/mastodon', 'action'=>'delete', 'id'=>$model['id']], [
                                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                    'data-method' => 'post',
                                ]);
                            },
                        ],
                ]
            ],
        ]); ?>
    <?php else: ?>
        <p><?= Yii::t('app', 'No scheduled posts.') ?></p>
    <?php endif ?>
    
</div>
<hr>
<div class="mastodon-post-form">

    <?php $form = ActiveForm::begin(['action'=>'mastodon?action=schedule', 'options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'status')->textarea(['rows' => 6]) ?>
    
    <?= $form->field($model, 'image')->fileInput() ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6])->hint(Yii::t('app', 'A meaningful description of the image to improve accessibility.')) ?>

    <?= $form->field($model, 'at')->textInput(['type'=>'datetime-local', 'min'=>date('Y-m-d\TH:i')]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Schedule'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
