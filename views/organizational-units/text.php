<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrganizationalUnitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Text Messages to Organizational Units');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Organizational Units'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs(
    "
    $('form').on('submit', function(event){
        // Check if form is valid
        if (!this[0].checkValidity()) {
            event.preventDefault();
            return false;
        }
        $('#loader').show();
    });
    ",
    \yii\web\View::POS_END,
    'loader_manager'
);

?>
<div class="organizational-unit-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=Html::beginForm(['send-sms-messages'],'post');?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn', 'name'=>'selection'],
            'name',
            'email:email',
            'phone',
        ],
    ]); ?>
    
    <div style="margin-top: 20px;">
        
        <div style="margin-top: 10px;">
            <?= Html::label(Yii::t('app', 'SMS Message:')) ?>
            <?= Html::textarea('sms_message', $text, [
                'class' => 'form-control',
                'rows' => 4,
                'placeholder' => Yii::t('app', 'Enter the message to send...'),
                'required' => true,
            ]) ?>
        </div>
        
        <div style="margin-top: 10px;">
            <?= Html::submitButton(Yii::t('app', 'Send'), [
                'class' => 'btn btn-primary loader',
                'name' => 'action',
                'value' => 'sendsms'
            ]) ?> <img style="display: none" id="loader" src="<?= Url::to('@web/images/submit_loader.gif') ?>" />
        </div>
    </div>
    
    <?= Html::endForm();?>

</div>

