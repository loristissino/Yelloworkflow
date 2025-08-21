<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Notifications Settings');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Profile'), 'url' => ['site/profile']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Notifications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Settings');

?>
<div class="notifications-settings">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?php $form = ActiveForm::begin(['action'=>Url::to(['notifications/settings']), 'options'=>['method'=>'POST']]); ?>

    <?= $form->field($model, 'notifications')->checkBoxList($model->available_notifications, ['separator'=>'<br>'])->label(Yii::t('app', 'Select the notifications you want to receive via email:')) ?>
    
    <p><?= Yii::t('app', 'Regardless of your choice, the notifications will always be available on the web application:') ?> <?= Html::a('➡️✉️', ['notifications/index'], ['style'=>'text-decoration: none']) ?></p>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Apply'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
