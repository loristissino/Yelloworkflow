<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Notification */

$this->title = $model->subject;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Notifications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="notification-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'subject',
            //'plaintext_body:ntext',
            //'html_body:raw',
            [
                'attribute' => 'html_body',
                'format' => 'raw',
                'label' => Yii::t('app','Content'),
            ],
            [
                'attribute' => 'created_at',
                'format' => 'raw',
                'label' => Yii::t('app','Created At'),
                'value' => function($data)
                {
                    return sprintf('%s %s', Yii::$app->formatter->asDate($data['created_at']), Yii::$app->formatter->asTime($data['created_at']));
                }
            ],
            [
                'attribute' => 'seen_at',
                'format' => 'raw',
                'label' => Yii::t('app','Seen At'),
                'value' => function($data)
                {
                    return sprintf('%s %s', Yii::$app->formatter->asDate($data['seen_at']), Yii::$app->formatter->asTime($data['seen_at']));
                }
            ],
            [
                'attribute' => 'sent_at',
                'format' => 'raw',
                'label' => Yii::t('app','Sent At'),
                'value' => function($data)
                {
                    return sprintf('%s %s', Yii::$app->formatter->asDate($data['sent_at']), Yii::$app->formatter->asTime($data['sent_at']));
                }
            ],
            'email:email',
        ],
    ]) ?>

</div>
