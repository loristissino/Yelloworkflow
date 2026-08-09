<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Product */

$this->title = Yii::t('app', 'Create Product');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products'), 'url' => ['manage']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('app', 'It is not possible to create a product from scratch: it is much better to clone an existing one <b>of the same kind</b>, so that common attributes are copied.') ?></p>
    <?php /*
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    */ ?>

</div>
