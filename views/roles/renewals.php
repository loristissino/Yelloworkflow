<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'Renewals Check');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Roles'), 'url' => ['reports/index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs(
    "
    $('.email-list-toggler').click(function(){
        console.log($(this));
        $(this).next().toggle();
    })
    
        ",
    \yii\web\View::POS_READY,
    'transaction_js_code_ready'
);

?>
<div class="role-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php foreach($roles as $role): $renewed=[]; $not_renewed=[]; ?>
        <h2 id="<?= $role->name ?>"><?= $role->description ?></h2>
        <ul>
        <?php //foreach($role->getUsers()->actuallyMember()->orderBy(['last_name' => SORT_ASC, 'first_name'=> SORT_ASC])->all() as $user):?>
        <?php foreach($role->getActiveUsersWithEmailAssociatedToRole() as $user):?>
            <li>
                <?= Html::a($user->fullName, ['users/view', 'id'=>$user->id]) ?>
                <?php if(!$user->isMember): ?>
                    <?= ' ⚠️ ' . Yii::t('app', 'Last Renewal: {year}', ['year'=>$user->last_renewal]) ?>
                    <?php $not_renewed[] = $user->formattedEmail ?>
                <?php else: ?>
                    <?php $renewed[] = $user->formattedEmail ?>
                    <?php if($user->hasRenewedForNextYear): ?>
                        <?= ' 💙 ' . Yii::t('app', 'Already Renewed for {year}', ['year'=>$user->last_renewal]) ?> 
                    <?php endif ?>
                <?php endif ?>
            </li>
        <?php endforeach ?>
        </ul>
        <div class="email-list">
            <p id="<?= $role->name ?>-renewed" class="email-list-toggler" title="<?= Yii::t('app', 'Toggle email addresses view') ?>" style="cursor: pointer">✉️</p>
            <div style="display: none">
                <?php if(sizeof($renewed)>0): ?>
                <h3>💙 <?= Yii::t('app', 'Users who have renewed their membership') ?></h3>
                <textarea cols="80"><?= join(",\n", $renewed) ?></textarea>
                <?php endif ?>
                <?php if(sizeof($not_renewed)>0): ?>
                <h3>😡️ <?= Yii::t('app', 'Users who have not renewed their membership') ?></h3>
                <textarea cols="80"><?= join(",\n", $not_renewed) ?></textarea>
                <?php endif ?>
            </div>
        </div>
    <?php endforeach ?>
