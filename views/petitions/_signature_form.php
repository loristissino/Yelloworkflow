<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;

$districts = \app\models\PetitionSignature::getDistricts();

//$ca = Yii::$app->controller->createCaptchaAction();
//$code = $ca->getVerifyCode();


/* @var $this yii\web\View */
/* @var $model app\models\PetitionSignature */
/* @var $form yii\widgets\ActiveForm */
?>

<a name="petition-form"></a>
<div class="envolve errors"></div>
<div class="envolve petition-signature-form">

    <?php $form = ActiveForm::begin(['action'=>$model->processingUrl, 'options'=>['method'=>'POST']]); ?>

    <fieldset style="background-color: #ADD8E6; margin-bottom: 10px;">
        <legend style="background-color: #4D4D4D; color: white; font-size: 0.8em; font-style: italic; margin-top: 10px;"><?= Yii::t('app', 'Info needed for the signature') ?></legend>
        <?= $form->field($model, 'petition_slug')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'type'=>'email'])->hint(Yii::t('app', 'The email address will not be published but will be used for the confirmation of the signature.')) ?>

        <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
    </fieldset>

    <fieldset style="background-color: #ADD8E6; margin-bottom: 10px;">
        <legend style="background-color: #4D4D4D; color: white; font-size: 0.8em; font-style: italic; margin-top: 10px;"><?= Yii::t('app', 'Info used for statistical reasons (will not be published)') ?></legend>
        <?= $form->field($model, 'yob')->textInput(['maxlength' => true, 'type'=>'number', 'min'=>1900, 'max'=>2005]) ?>

        <?= $form->field($model, 'district')->textInput(['maxlength' => true, 'list'=>'districts']) ?>

        <?= $form->field($model, 'gender')->radioList(['M'=>Yii::t('app', 'Male'), 'F'=>Yii::t('app', 'Female'), 'N'=>Yii::t('app', 'Non&nbsp;binary'), '-'=>Yii::t('app', 'Not&nbsp;specified')], ['encode'=>false, 'separator'=>'<br>', 'itemOptions'=>['style'=>'white-space:nowrap;'] ]) ?>        
    </fieldset>

    <fieldset style="background-color: #ADD8E6; margin-bottom: 10px;">
        <legend style="background-color: #4D4D4D; color: white; font-size: 0.8em; font-style: italic; margin-top: 10px;"><?= Yii::t('app', 'A text that (if you write it) will be published in association with your name amongst the signatures') ?></legend>
        <?= $form->field($model, 'message')->textarea(['rows' => 6, 'cols'=> 40])->hint(Yii::t('app', 'Up to 1000 characters.')) ?>
    </fieldset>
    
    <hr>
    
    <div class="form-group required">
        <div id="captcha_container">
            <img id="captcha_image" src="<?= $model->captcha ?>" alt="CAPTCHA" class="captcha-image">&nbsp;<span class="refresh-captcha" style="cursor: pointer" title="<?= Yii::t('app', 'Refresh') ?>">🔁</span>
        </div>
        <label for="petitionsignature-captcha" class="control-label"><?= Yii::t('app', 'Please Enter the Captcha Text') ?></label>
        <input type="text" id="petitionsignature-captcha" name="captcha_challenge" class="form-control">
    </div>
    
    <div class="form-group">
        <label><input type="checkbox" id="petitionsignature-accepted_terms" name="PetitionSignature[accepted_terms]" <?= $model->accepted_terms=='on'?'checked':'' ?>><span id="accepted_terms_text"><?= Yii::t('app', "I read the <a href='{url}' target='_blank'>privacy policy</a> and agreed to it.", ['url'=>Yii::$app->params['petitions'][$key]['privacy_policy_url']]) ?></span></label><br>
        <label><input type="checkbox" id="petitionsignature-agreed-to-keep-me-updated" name="PetitionSignature[agreed_to_keep_me_updated]" <?= $model->agreed_to_keep_me_updated=='on'?'checked':'' ?>><span id="agreed-to-keep-me-updated_text"><?= Yii::t('app', 'I ask to be kept informed on the updates related to this petition.') ?></span></label><br>
        <label><input type="checkbox" id="petitionsignature-agreed-to-allow-to-be-contacted" name="PetitionSignature[agreed_to_allow_to_be_contacted]" <?= $model->agreed_to_allow_to_be_contacted=='on'?'checked':'' ?>><span id="agreed-to-allow-to-be-contacted_text"><?= Yii::t('app', 'I agree to be contacted about other initiatives of this organization.') ?></span></label>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Sign<br><small style="line-height: 10px;">You will need to click<br>on the link sent by email<br>to finalize the signature process</small>'), ['id'=>'sign_button', 'class' => 'btn btn-success']) ?>
    </div>

    <datalist id="districts">
        <?php foreach($districts as $key=>$value): ?>
        <option value="<?=$key?>"><?=$value ?></option>
        <?php endforeach ?>
    </datalist>

    <?php ActiveForm::end(); ?>
    
    <script>
        document.querySelector('.refresh-captcha').onclick = function() {
            document.querySelector('.captcha-image').src = "<?= $model->captcha ?>?"+Date.now();
            let captchaEl = document.querySelector('#petitionsignature-captcha');
            captchaEl.value = "";
            captchaEl.focus();
        }
        
    </script>

</div>
