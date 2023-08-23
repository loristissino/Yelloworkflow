<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PeriodicalReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

if ($before=='2100-01-01') {
    $this->title = Yii::t('app', 'Balances');
    $b=null;
}
else {
    $this->title = Yii::t('app', 'Balances (transactions before {date})', ['date'=>Yii::$app->formatter->format($before, 'date')]);
    $b=$before;
}

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Periodical Reports'), 'url'=>['periodical-reports-management/index']];
$this->params['breadcrumbs'][] = $this->title;

$style=<<<CSS

table thead tr th {
  /* Important */
  background-color: white;
  position: sticky;
  z-index: 100;
  top: 0;
  padding-top: 50px !important;
}

#transactionstatusesform-weights label {
    font-weight: normal;
}

CSS;

$this->registerCss($style);

$columns = [
    [
        'attribute'=>Yii::t('app', 'Organizational Unit'),
        'format' => 'raw',
        'value'=>function($data) {
            if ($data['status']==0){
                return '<span style="text-decoration: line-through;">' .$data['ou'] . '</span>';
            }
            return $data['ou'];
        }
        
    ],
    [
        'label'=>Yii::t('app', 'Ceiling Amount'),
        'format' => 'raw',
        'value'=> 
        function($data) {
            if (!isset($data[Yii::$app->params['periodicalReports']['accountToCompareToCeilingAmount']])) {
                return "check the params: ['periodicalReports']['accountToCompareToCeilingAmount']";
            }
            $difference = $data[Yii::$app->params['periodicalReports']['accountToCompareToCeilingAmount']] - $data['ceiling_amount'];
            $prefix = ($difference > 0) ? '<span style="cursor: help;" title="' . Yii::t('app', 'The Ceiling Amount has been exceed of {amount}.', ['amount'=>Yii::$app->formatter->asCurrency($difference)]) .'">🔔</span> ': '';
            return $prefix . Yii::$app->formatter->asCurrency($data['ceiling_amount']);
        }
        ,
        'contentOptions' => ['class' => 'amount', 'style'=>'color: #0000FF'],
        'headerOptions' => ['style' => 'white-space: normal'],
        'footerOptions' => ['class' => 'amount'],    ],
];

foreach ($fields as $field) {
    $columns[] = [
        'attribute' => $field,
        'format' => 'raw',
        'label' => $field,
        'value' => function($data) use ($field, $enforcedBalances) {
            if ($data[$field] == 0) {
                return '';
            }
            else {
                $fv = Yii::$app->formatter->asCurrency($data[$field]);
                
                $prefix = '';
                if (($enforcedBalances[$field]=='C' and $data[$field]>0) or ($enforcedBalances[$field]=='D' and $data[$field]<0)) {
                    $prefix = '<span style="cursor: help;" title="' . Yii::t('app', 'This balance is inconsistent with the account definition.') .'">🔔</span> ';
                }
                
                return $prefix . ($data[$field]>0? $fv: '<span style="color: red">'.$fv.'</span>');
            }
        },
        'contentOptions' => ['class' => 'amount'],
        'headerOptions' => ['style' => 'white-space: normal'],
        'footerOptions' => ['class' => 'amount'],
    ];
    
}

?>
<div class="balances-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>💡 <?= Yii::t('app', 'Check at the bottom of the page which transactiions are taken into consideration.') ?><br><?= Yii::t('app', 'Only organizational units which have their own cash are shown.') ?></p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $columns,
        'summary' => '',
        ]); 
    ?>

</div>
<a name="statuses"></a>
<?php $form = ActiveForm::begin(['action'=>Url::to(['site/set-preference', 'returnUrl'=>$model->url]), 'options'=>['method'=>'POST']]); ?>

<?= $form->field($model, 'weights')->checkBoxList($model->values, ['separator'=>'<br>'])->label(Yii::t('app', 'Select the transactions you want to be included in the computation:')) ?>

<div class="form-group">
    <?= Html::submitButton(Yii::t('app', 'Apply'), ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>

<hr>

<?= Yii::t('app', 'You can set a limit date for transactions to be considered:') ?>
<form><input type="date" name="before" value="<?= $b?>"><br>
<input type="submit" class="btn btn-success" value="<?= Yii::t('app', 'Apply') ?>"></form>
    
