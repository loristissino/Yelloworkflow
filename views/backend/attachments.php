<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Attachments');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Back End'), 'url' => ['backend/index']];
$this->params['breadcrumbs'][] = $this->title;

$module = \Yii::$app->getModule('attachments');

$stats = [
    'mf'=>[
        'description'=>Yii::t('app', 'Missing file'),
        'count'=>0
    ],
    'ws'=>[
        'description'=>Yii::t('app', 'Wrong size'),
        'count'=>0
    ],
    'wh'=>[
        'description'=>Yii::t('app', 'Wrong hash'),
        'count'=>0
    ],
    'ok'=>[
        'description'=>Yii::t('app', 'OK'),
        'count'=>0
    ]
];

function getFullPath($hash, $type, $module) {
    return sprintf('%s/%s.%s', $module->getFilesDirPath($hash), $hash, $type);
}

?>

<div class="attachment-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'hash',
            [
                'attribute'=>'name',
                'format'=>'raw',
                'value'=>function($data) {
                    return Html::a(
                        sprintf('%s.%s', $data['name'], $data['type']),
                        Url::to(['/attachments/file/download', 'id' => $data['id'], 'hash'=>$data['hash']]), 
                        ['target'=>'_blank']
                    );
                }
            ],
            'model',
            [
                'attribute'=>'itemId',
                'format'=>'raw',
                'value'=>function($data) {
                    
                    return Html::a(
                        $data['itemId'],
                        Url::to(['activities/index', 'ActivitytSearch[model]'=>$data['model'], 'ActivitytSearch[model_id]'=>$data['itemId']]),
                        ['title'=>Yii::t('app', 'See activities')]
                    );
                }
            ],
            
            
            
            [
                'header'=>Yii::t('app', 'Check'),
                'format'=>'raw',
                'value'=>function($data) use($module, &$stats) {
                    $path = getFullPath($data['hash'], $data['type'], $module);
                    if (!file_exists($path)){
                        $key = 'mf';
                        $stats[$key]['count']++;
                        return $stats[$key]['description'];
                    }
                    if (filesize($path)!==$data['size'])
                    {
                        $key = 'ws';
                        $stats[$key]['count']++;
                        return $stats[$key]['description'];
                    }
                    if (md5_file($path)!==$data['hash'])
                    {
                        $key = 'wh';
                        $stats[$key]['count']++;
                        return $stats[$key]['description'];
                    }
                    $key = 'ok';
                    $stats[$key]['count']++;
                    return $stats[$key]['description'];
                }
            ],
            
        ],
    ]); ?>


</div>
<hr>

<ul>
<?php foreach($stats as $key=>$stat): $color= ($stat['count']>0 and $key!='ok') ? 'red': 'green'; ?>
    <li style="color: <?= $color ?>"><?= sprintf('%s: %d', $stat['description'], $stat['count']) ?></li>
<?php endforeach ?>
</ul>
