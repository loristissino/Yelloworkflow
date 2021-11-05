<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->getFullName();
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Fix Permissions'), ['fix-permissions', 'id' => $model->id], [
            'class' => 'btn btn-info',
            'data' => [
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'first_name',
            'last_name',
            'email:email',
            [
                'label' => 'Status',
                'value' => $model->status == 1 ? 'Active': 'Inactive',
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>
    
    <hr />

    <h2><?= Yii::t('app', 'Project-Enabled Organizational Units') ?></h2>
    <?= \app\components\UnorderedListWidget::widget([
        'introMessage'=>'{count,plural,=0{No organizational unit found} =1{One organizational unit found} other{# organizational units found}}:',
        'items'=>$model->getOrganizationalUnits()->all(),
        'textProperty'=>'name',
        'link'=>'organizational-units/view',
        'noItemsMessage'=>Yii::t('app', 'This user does not belong to any project-enabled organizational unit.'),
    ]) ?>

    <h2><?= Yii::t('app', 'Authorizations') ?></h2>

    <h3><?= Yii::t('app', 'Roles') ?></h3>
    <?= \app\components\UnorderedListWidget::widget([
        'introMessage'=>'{count,plural,=0{No role found} =1{One role found} other{# roles found}}:',
        'items'=>$model->getRoles()->all(),
        'textProperty'=>'description',
        'link'=>'roles/view',
    ]) ?>
    
    <h3><?= Yii::t('app', 'Specific Authorizations') ?></h3>

    <?= \app\components\UnorderedListWidget::widget([
        'introMessage'=>'{count,plural,=0{No authorization found} =1{One authorization found} other{# authorizations found}}:',
        'items'=>$model->getAuthorizations()->active()->all(),
        'textProperty'=>'identifier',
        'link'=>'authorizations/view',
    ]) ?>

    <?php /*
    <h3>Generic Authorizations</h3>

    <?= \app\components\UnorderedListWidget::widget([
        'introMessage'=>'{count,plural,=0{No authorization found} =1{One authorization found} other{# authorizations found}}:',
        'items'=>$generic_authorizations,
        'textProperty'=>'identifier',
        'link'=>'authorizations/view',
    ]) ?>    
    */ ?>

</div>
