<?php

use Yii;
use yii\helpers\Html;

$output='';
if ($o = Yii::$app->session['shortener_output']) {
    $output = implode("\n", array_map(function($row) { return implode("\t", $row); }, $o));
}

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Newly Created Shortenings');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shortenings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$this->registerJs(
    "
    document.getElementById('copy-btn').addEventListener('click', function() {
        const textarea = document.getElementById('output-textarea');
        textarea.select();
        document.execCommand('copy');
        this.textContent = '✓ " . Yii::t('app', 'Copied!') . "';
        setTimeout(() => { this.textContent = '📋 " .  Yii::t('app', 'Copy') . "'; }, 2000);
    });
    ",
    \yii\web\View::POS_END,
    'copy_and_paste'
);

?>
<div class="shortenings-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if($output): ?>
        <div style="gap: 10px; margin-bottom: 10px;">
            <button id="copy-btn" type="button" title="<?= Yii::t('app', 'Copy to clipboard') ?>" style="border: none; background: none; cursor: pointer; color: inherit; padding: 0;">📋 <?= Yii::t('app', 'Copy') ?></button>
            <br>
            <textarea id="output-textarea" rows="<?= sizeof($o)+1 ?>" readonly style="width: 100%; overflow-x: auto; white-space: pre; tab-size: 4; box-sizing: border-box;"><?= $output ?></textarea>
        </div>
        <div><p><?= Yii::t('app', 'This is the output of the shortener service. You can copy and paste it back to your spreadsheet.')?></p></div>
    <?php else: ?>
        <p><?= Yii::t('app', 'There is no shortener\'s output.') ?></p>
    <?php endif ?>
    
    <hr>
    
    <p><?= Html::a(Yii::t('app', 'Create Shortenings'), ['create', 'multiline'=>1]) ?></p>

</div>
