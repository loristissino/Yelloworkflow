<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Social Media');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="social-media-index">
    <h1><?= Html::encode($this->title) ?></h1>

<div style="font-size: 1.5em">
<p><?= Html::a(Yii::t('app', 'Mastodon'), ['mastodon']) ?></p>
</div>

</div>
