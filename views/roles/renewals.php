<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'Renewals Check');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Roles'), 'url' => ['reports/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php foreach($roles as $role): ?>
        <h2><?= $role->description ?></h2>
        <ul>
        <?php foreach($role->getUsers()->all() as $user): ?>
            <li>
                <?= Html::a($user->fullName, ['users/view', 'id'=>$user->id]) ?>
                <?php if(!$user->isMember): ?>
                    <?= ' ⚠️ ' . Yii::t('app', 'Last Renewal: {year}', ['year'=>$user->last_renewal]) ?> 
                <?php endif ?>
            </li>
        <?php endforeach ?>
        </ul>
        
    <?php endforeach ?>
