<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Role */

$this->title = $model->description;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$this->registerJs(
    "
    $('#toggle_addresses_link').click(function(e){
        e.preventDefault();
        $('#addresses').toggle();
    });
    ",
    \yii\web\View::POS_END,
    'togglea_ddresses_view'
);

?>
<div class="role-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'rank',
            [
                'label' => 'Status',
                'value' => $model->status == 1 ? 'Active': 'Inactive',
            ],
            'name',
            'description',
            'permissions',
            'email:email',
        ],
    ]) ?>

    <hr />

    <h2><?= Yii::t('app', 'Users with this Role') ?></h2>
    <?= \app\components\UnorderedListWidget::widget([
        'introMessage'=>'{count,plural,=0{No user found} =1{One user found} other{# users found}}:',
        'items'=>$model->getUsers()->all(),
        'textProperty'=>'fullname',
        'link'=>'users/view',
    ]) ?>
    
    <a href="#" id="toggle_addresses_link"><?= Yii::t('app', 'Toggle email addresses view') ?></a><br />
    <textarea id="addresses" style="display:none" cols="90" rows="5"><?= $model->getFormattedUsersEmailAddresses() ?></textarea>

</div>
