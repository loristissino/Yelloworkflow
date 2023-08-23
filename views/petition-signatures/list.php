<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Markdown;

use app\assets\PagedAsset;

PagedAsset::register($this);

/* @var $this yii\web\View */

$this->title = $petition->title;

$style=<<<CSS

.wrap>.container {
    padding: 0px 15px 20px;
}

h1 {
  font-size: 1.5em;
}

div.target {
    margin-left: 80mm;
}

ul.signatures-list {
  list-style: none;
  margin-left: 0;
  padding-left: 0;
}

li.envolve.signature {
    color: black;
    padding-left: 1em;
    text-indent: -1em;
}

li.envolve.signature:before {
  content: "✍️";
  padding-right: 5px;
}

div.envolve.message {
    color: gray;
    font-size: 1em;
    background-color: white;
    padding-left: 2.5em;
    text-indent: 0em;
}

div.request {
    font-weight: bold;
}

footer {
    display: none;
}

.petition {
    font-size: 1.3em;
}

@media print {
    li {
        page-break-inside: avoid;
    }

    @page {

      size: 210mm 297mm;
      margin-top: 20mm;
      margin-right: 20mm;
      margin-bottom: 20mm;
      margin-left: 15mm;

      @bottom-left {
        content: 'firma.uaar.it'
      }

      @bottom-right {
        content: 'pag. ' counter(page) '/' counter(pages);
      }

    }

}


CSS;

$this->registerCss($style);

?>

<div style="line-height: 10px"><img src="https://www.sbattezzati.it/images/logo_uaar.png" style="width:25%; height:auto"><br>
<span style="font-size: 0.7em">Unione degli Atei e degli Agnostici Razionalisti<br>
Via Francesco Negri, 67/69 - 00154 Roma</span>
</div>

<h1><?= Html::encode($this->title) ?></h1>

<div class="petition">
    <div class="target"><?= Markdown::process($petition->target) ?></div>

    <div class="introduction"><?= Markdown::process($petition->introduction) ?></div>

    <div class="request"><?= Markdown::process($petition->request) ?></div>
</div>

<div class="signatures">

    <p>Petizione promossa dal Circolo Uaar di Roma</p>
    <ul class="signatures-list">
    <?php foreach($signatures->all() as $signature): ?>
        <li class="envolve signature">
            <span class="signature" title="<?= $signature->id ?>"><?= Html::encode($signature->fullName) ?></span>
            <?php if ($signature->message && $with_messages): ?>
                <div class="envolve message">
                    <?= Html::encode($signature->message) ?>
                </div>
            <?php endif ?>
        </li>
    <?php endforeach ?>
    </ul>

<p><?= Yii::t('app', 'Signatures: {number}.', ['number'=>$signatures->count()]) ?>

<p>Roma, <?= date('d/m/Y') ?></p>

</div>
