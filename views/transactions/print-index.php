<?php

use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/*
 */
$this->title = Yii::t('app', 'Transactions');
//$this->params['breadcrumbs'][] = $this->title;

?>
<div class="transaction-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <?php if (($dataProvider->count > 0)): ?>

    <?=Html::beginForm(['process'],'post');?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'summary' => '',
        'columns' => [
            [
                'label'=>Yii::t('app', 'Date / Description / Project'),
                'format'=>'raw',
                'value'=>function($data) {
                    $html = '';
                    $html =  sprintf('<strong>%s</strong><br /><em>%s</em>',
                        Yii::$app->formatter->asDate($data['date']),
                        $data['description']
                    );
                    
                    if ($data['project']) {
                        $html .= sprintf('<br />[%s]', $data['project']['title']);
                    }
                    return $html;
                },
            ],
            [
                'label' => Yii::t('app', 'Postings'),
                'format' => 'raw',
                'value' => 'postingsPrintingView'
            ],
            [
                'label' => Yii::t('app', 'Status'),
                'format'=>'raw',
                'value'=>'workflowLabel',
            ],
        ]
    ]); ?>
    
    <?= Html::endForm();?>

    <?php else: ?>

    <p><?= Yii::t('app', 'No transactions.') ?></p>

    <?php endif ?>

</div>
