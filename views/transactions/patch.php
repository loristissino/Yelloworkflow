<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $transaction app\models\Transaction */
/* @var $postingsText string */
/* @var $previewOutput string|null */

$this->title = Yii::t('app', 'Patch Transaction #{id}', ['id'=>$transaction->id]);

$accounts = \app\models\Account::find()->active()->all();

?>

<div class="transaction-patch">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!empty($previewOutput)): ?>
        <div class="preview-block" style="margin-bottom: 25px;">
            <label class="control-label text-info"><strong>📋 <?= Yii::t('app', 'Journal Entry Preview (Unsaved)') ?></strong></label>
            <pre style="background: #f8f9fa; padding: 15px; border: 2px dashed #17a2b8; font-family: monospace; line-height: 1.4; color: #333;">
<?= Html::encode($previewOutput) ?>
            </pre>
        </div>
    <?php endif; ?>

    <?= Html::beginForm(['patch', 'id' => $transaction->id], 'post') ?>

        <div class="form-group mb-3">
            <?= Html::label(Yii::t('app', 'Description'), 'description', ['class' => 'control-label']) ?>
            <?= Html::textInput('description', $transaction->description, ['class' => 'form-control', 'required' => true]) ?>
        </div>

        <div class="form-group mb-3">
            <?= Html::label(Yii::t('app', 'Reason'), 'reason', ['class' => 'control-label']) ?>
            <?= Html::textInput('reason', $reason, ['class' => 'form-control', 'required' => true]) ?>
        </div>

        <div class="form-group mb-4">
            <?= Html::label(Yii::t('app', 'Postings (Account ID [TAB] Amount)'), 'postings_text', ['class' => 'control-label']) ?>
            <?= Html::textarea('postings_text', $postingsText, [
                'class' => 'form-control', 
                'rows' => 10, 
                'style' => 'font-family: monospace;',
                'placeholder' => "1001\t500.00\n1002\t-500.00"
            ]) ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app','Preview'), [
                'class' => 'btn btn-info me-2', 
                'name' => 'submit_type', 
                'value' => 'preview'
            ]) ?>
            
            <?= Html::submitButton(Yii::t('app', 'Save'), [
                'class' => 'btn btn-success me-2', 
                'name' => 'submit_type', 
                'value' => 'save'
            ]) ?>
            
            <?= Html::a(Yii::t('app', 'Cancel'), ['view', 'id' => $transaction->id], ['class' => 'btn btn-secondary']) ?>
        </div>

    <?= Html::endForm() ?>

</div>

<div>
<h1><?=yii::t('app', 'Available Accounts') ?></h1>
<pre style="background: #f8f9fa; padding: 15px; border: 2px dashed #17a2b8; font-family: monospace; line-height: 1.4; color: #333;">Id    <?=yii::t('app', 'Name') . "\n"?><?=str_repeat("-", 75) . "\n" ?>
<?php foreach($accounts as $account): ?><?=sprintf("%-5s %s\n", $account->id, $account->name) ?><?php endforeach ?></pre>
</div>
