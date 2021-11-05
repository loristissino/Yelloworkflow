<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Account Help');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Login'), 'url' => ['site/login']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs(
    "
    $('.loader').on('click', function(event){
        $('#loader').show();
    });
    ",
    \yii\web\View::POS_END,
    'loader_manager'
);

?>
<div class="site-pwdreset">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('app', 'Please provide your username or your email address:') ?></p>

    <?php $form = ActiveForm::begin([
        'id' => 'pwdreset-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton(Yii::t('app', 'Send Email'), ['class' => 'btn btn-primary loader', 'name' => 'login-button']) ?>
                <img style="display: none" id="loader" src="<?= Url::to('@web/images/submit_loader.gif') ?>" />

            </div>

        </div>

    <?php ActiveForm::end(); ?>
</div>
