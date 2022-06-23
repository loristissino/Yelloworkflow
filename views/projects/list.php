<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', $status == 'approved' ? 'Approved Projects': ($status=='completed' ? 'Completed Projects': 'Projects'));
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Projects'), 'url' => ['project-submissions/list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if($dataProvider->count > 0): ?>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            //return Html::a(Html::encode($model->title), ['card', 'id' => $model->id]) . 'key='.$key . 'index='.$model->id;
            return $this->render('card', ['model'=>$model]);
        },
    ]) ?>
    
    <?php else: ?>
        <p><?= Html::a(Yii::t('app', 'Approved Projects'), ['list', 'status'=>'approved']) ?></p>
        <p><?= Html::a(Yii::t('app', 'Completed Projects'), ['list', 'status'=>'completed']) ?></p>
    <?php endif ?>


</div>
