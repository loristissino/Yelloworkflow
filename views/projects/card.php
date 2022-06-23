<?php

use yii\helpers\Html;

?>
<div class="project-card-view">

    <h2><?= Html::encode($model->title) ?></h2>
    
    <p>
        <strong><?= Html::encode($model->place) ?></strong>, <?= Html::encode($model->period) ?>
        <?= Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['project-submissions/details', 'id'=>$model->id], [
            'title'=>Yii::t('app', 'View'),
        ]) ?>
    </p>

    <hr />

</div>
