<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = Yii::t('app', 'Organizational Units');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Profile'), 'url' => ['site/profile']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p><?= Yii::t('app', 'Choose the Organizational Unit you want to work for in this session.') ?></p>
    <?php foreach($organizational_units as $ou): ?>
        <?= Html::a($ou->name, ['choose-organizational-unit', 'id'=>$ou->id, 'return'=>$return], ['data-method'=>'post', 'class'=>'btn btn-primary'])?>
    <?php endforeach ?>
    
</div>
