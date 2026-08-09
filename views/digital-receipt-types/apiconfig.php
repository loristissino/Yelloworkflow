<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DigitalReceiptType */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Digital Receipt Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id'=>$model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Configuration');

$json = json_decode($configuration, true);
$taxCode = $json['data']['receipts_authentication']['taxCode'];
$apiForm->taxCode = $taxCode;

?>
<div class="digital-receipt-type-config">

    <?php if(Yii::$app->session->hasFlash('api_result', false)): ?>
    
    <h1><?= Html::encode(Yii::t('app', 'Update Result')) ?></h1>

    <?= $model->getJsonFieldAsHtml(Yii::$app->session->getFlash('api_result'), true) ?>

    <?php endif ?>

    <h1><?= Html::encode(Yii::t('app', 'Configuration')) ?></h1>

    <?= $model->getJsonFieldAsHtml($configuration, true) ?>

    <hr>
    
    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($apiForm, 'taxCode')->textInput() ?>
    <?= $form->field($apiForm, 'password')->textInput() ?>
    <?= $form->field($apiForm, 'pin')->textInput() ?>

    <?= \yii\helpers\Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>

    <?php \yii\widgets\ActiveForm::end(); ?>

</div>
