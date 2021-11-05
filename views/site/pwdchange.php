<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Password Change');
$this->params['breadcrumbs'][] = $this->title;

$pwdLabel = 'Password <span id="toggle_password_visibility" title="' . Yii::t('app', 'Toggle password visibility') . '" style="cursor: pointer"><span id="password_eye">👁️</span><span id="password_lock" style="display: none">🔒</span></span>';

$this->registerJs("

    let passwordShown=false;
    jQuery('#toggle_password_visibility').click(function(e){
        e.preventDefault();
        passwordShown = !passwordShown;
        jQuery('#password_eye').toggle();
        jQuery('#password_lock').toggle();
        jQuery('#pwdchangeform-password').attr('type',passwordShown?'text':'password').focus();
    })
    
    ", \yii\web\View::POS_END);


?>
<div class="site-pwdchange">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('app', 'Choose a new password.') ?></p>

    <?php $form = ActiveForm::begin([
        'id' => 'pwd-change-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'password')->passwordInput(['autofocus' => true])->label($pwdLabel) ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>

        </div>

    <?php ActiveForm::end(); ?>
</div>
