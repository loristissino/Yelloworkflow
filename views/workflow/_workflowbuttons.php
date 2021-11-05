<?php

use yii\helpers\Html;
use yii\helpers\Url;

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
<?php foreach($transitions as $transition):
    $es=$transition->getEndStatus();
    $condition=$es->getMetadata('condition');
    $confirmCondition=$es->getMetadata('confirmCondition', false);
?>
<?php if ($condition && ! $model->$condition) continue ?>
<?php
$data = [
    'method' => 'post'
];
$confirm = $es->getMetadata('confirm');

if ($confirm && ( ! $confirmCondition or ($confirmCondition and $model->$confirmCondition) ) ) {
    $data['confirm'] = Yii::t('app', $confirm);
}

?>
<?= Html::a(Yii::t('app', $es->getMetadata('verb')), ['change', 'id' => $model->id, 'status'=>$es->getId()], [
    'class' => 'btn btn-info loader',
    'style' => 'background-color: ' . $es->getMetadata('color') . '; border-color: ' . $es->getMetadata('color'),
    'data' => $data,
]) ?> 
<?php endforeach ?>
<img style="display: none" id="loader" src="<?= Url::to('@web/images/submit_loader.gif') ?>" />
