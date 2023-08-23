<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Documents');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Periodical Reports'), 'url' => ['periodical-report-submissions/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="attachment-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
			['class' => 'yii\grid\CheckboxColumn'],
            [
                'label'=>Yii::t('app', 'Date'),
                'attribute'=>'date',
                'format'=>'raw',
                'value'=>function($data) {
                    return Yii::$app->formatter->asDate($data['created_at']);
                },
            ],
            [
                'label' => Yii::t('app', 'Name'),
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a(
                        sprintf('%s.%s', $data['name'], $data['type']),
                        Url::to(['/attachments/file/download', 'id' => $data['id'], 'hash'=>$data['hash']]), 
                        ['target'=>'_blank']
                    );
                }
            ],
            [
                'label' => Yii::t('app', 'Description'),
                'format' => 'raw',
                'value' => function ($data) {
                    return $data['description'];
                },
            ],
        ],
    ]); ?>


</div>
