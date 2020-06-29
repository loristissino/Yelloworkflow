<?php if (sizeof(Yii::$app->user->identity->getOrganizationalUnits()->all())>1): ?>
    <p>
        <?= \Yii\helpers\Html::a(Yii::t('app', 'Need to change Organizational Unit?'), ['site/choose-organizational-unit', 'return'=>Yii::$app->request->url]) ?>
    </p>
<?php endif ?>
