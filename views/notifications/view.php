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
            'created_at:datetime',
            'seen_at:datetime',
            'sent_at:datetime',
            'email:email',
        ],
    ]) ?>

</div>
