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

        <?php if ($model->otp_secret): ?>
        <?= Html::a(Yii::t('app', 'Disable two-factor authentication'), ['disable-two-factor-authentication', 'id' => $model->id], [
            'class' => 'btn btn-warning',
            'data' => [
                'method' => 'post',
                'confirm' => Yii::t('app', 'Do you really want to disable two-factor authentication for this user?'),
            ],
        ]) ?>
        <?php endif ?>

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
            [
                'label' => Yii::t('app', 'Two-factor authentication'),
                'value' => $model->otp_secret ? 'Active': 'Inactive',
            ],
            [
                'label' => Yii::t('app', 'External Id'),
                'format' => 'raw',
                'value' => function($model) {
                    return Html::a($model->external_id, sprintf(Yii::$app->params['externalInfo']['usersUrl'], $model->external_id), ['target'=>'_blank']);
                }
            ],
            'last_renewal',
            [
                'attribute'=>'created_at',
                'format'=>'raw',
                'value'=>function($data) {
                    return sprintf('%s %s', Yii::$app->formatter->asDate($data['created_at']), Yii::$app->formatter->asTime($data['created_at']));
                },
            ],
            [
                'attribute'=>'updated_at',
                'format'=>'raw',
                'value'=>function($data) {
                    return sprintf('%s %s', Yii::$app->formatter->asDate($data['updated_at']), Yii::$app->formatter->asTime($data['updated_at']));
                },
            ],
            [
                'attribute'=>'last_action_at',
                'format'=>'raw',
                'value'=>function($data) {
                    return sprintf('%s %s', Yii::$app->formatter->asDate($data['last_action_at']), Yii::$app->formatter->asTime($data['last_action_at']));
                },
            ],
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
