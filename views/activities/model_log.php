<?php

use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ActivitytSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Workflow Log');
$this->params['breadcrumbs'][] = '…';
$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'label'=>Yii::t('app', 'Date'),
                'attribute'=>'happened_at',
                'format'=>'raw',
                'value'=>function($data) {
                    return sprintf('%s %s', Yii::$app->formatter->asDate($data['happened_at']), Yii::$app->formatter->asTime($data['happened_at']));
                },
            ],
            [
                'label' => Yii::t('app', 'User'),
                'attribute'=>'full_name',
                'format'=>'raw',
                'value'=>'user.fullName',
            ],
            [
                'label' => Yii::t('app', 'Activity'),
                'attribute'=>'activity_type',
                'format'=>'raw',
                'value'=>function($data) use ($model) {
                    $s = explode('/', $data['activity_type']);
                    $status = ucfirst(sizeof($s)>1 ? $s[1] : $s[0]);
                    return Yii::t(Yii::t('app', get_class($model)), $status);
                }
            ],
        ],
    ]); ?>
</div>

