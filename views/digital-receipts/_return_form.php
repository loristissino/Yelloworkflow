<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="return-form">
    <?php $form = ActiveForm::begin(); ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th><?= Yii::t('app', 'Description') ?></th>
                <th><?= Yii::t('app', 'Price') ?></th>
                <th><?= Yii::t('app', 'Purchased Qty') ?></th>
                <th><?= Yii::t('app', 'Qty to Return') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($model->items as $item): ?>
                <tr>
                    <td><?= $item['description'] ?></td>
                    <td><?= Yii::$app->formatter->asCurrency($item['unit_price']) ?>
                    <?php if ($item['discount']!=0): ?>
                        <br>
                        <em><small>- <?= Yii::$app->formatter->asCurrency($item['discount']) ?></small></em>
                    <?php endif ?>
                    </td>
                    <td><?= $item['quantity'] ?></td>
                    <td>
                        <?= $form->field($model, "returnQuantities[{$item['item_assigned_id']}]")
                            ->textInput(['type' => 'number', 'value'=>0, 'min' => 0, 'max' => $item['quantity']])
                            ->label(false) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <?= $form->field($model, 'reason')->textInput(['type' => 'text', 'maxlength' => true])?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Process Return'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
