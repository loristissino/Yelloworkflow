<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DigitalReceiptLineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model app\models\DigitalReceiptLine */
/* @var $index string */

?>
<tr id="row-<?= $index ?>">
    <td>
        <?= $model->description ?>
    </td>
    <td>
        <?= $model->quantity ?>
    </td>
    <td>
        <?= $model->unit_price ?>
    </td>
    <td id="total-<?= $index ?>">
        <?= number_format($model->quantity * $model->unit_price, 2) ?>
    </td>
</tr>
