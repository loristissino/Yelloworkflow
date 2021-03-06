<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
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
                'label' => 'Content',
                'format' => 'raw',
                'value' => 'html_body',
            ],
            'created_at:datetime',
            //'seen_at',
            //'sent_at',
            //'email:email',

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view}',
            ]        ],
    ]); ?>
    
    <?= Yii::t('app', 'With the selected notifications: ') ?>
    <?= Html::a("Mark seen", ['process', 'action'=>'markSeen'], ['data-method'=>'post'])?> - 
    <?= Html::a("Mark unseen", ['process', 'action'=>'markUnseen'], ['data-method'=>'post'])?>
    
    <?= Html::endForm();?> 

</div>
