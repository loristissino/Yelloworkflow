<?php

use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\NotificationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Notifications');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="notification-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?=Html::a(Yii::t('app', $seen ? 'Show unseen messages' : 'Show seen messages'), ['notifications/index', 'seen'=>$seen?'false':'true']) ?>
        
    <?=Html::beginForm(['process'],'post');?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
			['class' => 'yii\grid\CheckboxColumn'],

            'subject',
            //'plaintext_body:ntext',
            [
                'label' => Yii::t('app', 'Content'),
                'format' => 'raw',
                'value' => 'html_body',
            ],
            [
                'label'=>Yii::t('app', 'Created At'),
                'attribute'=>'created_at',
                'format'=>'raw',
                'value'=>function($data) {
                    return sprintf('%s %s', Yii::$app->formatter->asDate($data['created_at']), Yii::$app->formatter->asTime($data['created_at']));
                },
            ],


            //'seen_at',
            //'sent_at',
            //'email:email',

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view}',
                'buttons'=>[
                    'view' => function ($url, $model) use ($pagesize) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['notifications/view', 'id'=>$model->id, 'pagesize'=>$pagesize], [
                            'title'=>Yii::t('app', 'View'),
                            ]);
                        },
                ],
            ]
        ],
    ]); ?>
    
    <?= Yii::t('app', 'With the selected notifications: ') ?>
    <?= Html::a(Yii::t('app', 'Mark seen'), ['process', 'action'=>'markSeen'], ['data-method'=>'post'])?> - 
    <?= Html::a(Yii::t('app', 'Mark unseen'), ['process', 'action'=>'markUnseen'], ['data-method'=>'post'])?>
    
    <?= Html::endForm();?> 

</div>
