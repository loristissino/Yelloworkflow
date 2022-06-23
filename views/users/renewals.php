<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = Yii::t('app', 'Renewals Bulk Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="renewals">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="renewals-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'year')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'ids')->textArea(['maxlength' => true, 'cols'=>2, 'rows'=>10])->hint(Yii::t('app', 'Ids from external database, one by line.')) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Load Renewals'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
