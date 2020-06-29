<?php
use Yii\helpers\Html;
?>

<?php if($items): ?>
    <p><?= Yii::t('app', $introMessage, ['count'=>sizeof($items)]) ?></p>
    <?= Html::ul($items, ['item' => function($item, $index) use($textProperty, $link, $key) {
        return Html::tag(
            'li',
            $link ? Html::a($item->$textProperty, [$link, 'id'=>$item->$key]) : $item->$textProperty
        );
    }]) ?>
<?php else: ?>
    <p><?= $noItemsMessage ?></p>
<?php endif ?>
