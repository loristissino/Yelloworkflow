<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Login');
$this->params['breadcrumbs'][] = $this->title;

$pwdLabel = 'Password <span id="toggle_password_visibility" title="' . Yii::t('app', 'Toggle password visibility') . '" style="cursor: pointer"><span id="password_eye">👁️</span><span id="password_lock" style="display: none">🔒</span></span>';

$this->registerJs("

    let passwordShown=false;
    jQuery('#toggle_password_visibility').click(function(e){
        e.preventDefault();
        passwordShown = !passwordShown;
        jQuery('#password_eye').toggle();
        jQuery('#password_lock').toggle();
        jQuery('#loginform-password').attr('type',passwordShown?'text':'password').focus();
    })
    
    ", \yii\web\View::POS_END);

?>

<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php /*<h2>Internal Login</h2> */?>

    <p><?= Yii::t('app', 'Please fill out the following fields to login:') ?></p>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}\n{hint}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
            'hintOptions' => ['class' => 'col-lg-12'],
        ],
    ]); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'password')->passwordInput()->label($pwdLabel) ?>
     
        <?= $form->field($model, 'authMethod')->dropDownList($model->authMethods) ?>

        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
        ]) ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                &nbsp;&nbsp;&nbsp;<?= Html::a(Yii::t('app', 'Forgot your username / password?'), ['site/pwdreset']) ?>
            </div>

        </div>

    <?php ActiveForm::end(); ?>
    <?php /*
    <hr />
    
    <h2>SSO Login</h2>
    
    <div>
        <?= Html::a("Login via SSO on ...", ['site/sso']) ?>
    </div>
    */ ?>

</div>
