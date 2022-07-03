<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = Yii::t('app', 'Issues');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Yii::t('app', 'Please use this form to open an issue with the application.') ?>
    </p>

    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin(['id' => 'issues-form']); ?>

                <?= $form->field($model, 'reference')->textInput(['readonly'=>true, 'style'=>'width: 650px']) ?>

                <?= $form->field($model, 'url')->textInput(['readonly'=>true, 'style'=>'width: 650px']) ?>

                <?= $form->field($model, 'subject')->textInput(['style'=>'width: 750px']) ?>

                <?= $form->field($model, 'description')->textarea(['rows' => 6, 'style'=>'width: 750px']) ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary', 'name' => 'issues-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>

</div>
