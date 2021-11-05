<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\TransactionTemplatePosting;
use app\models\TransactionTemplatePostingSearch;

/* @var $this yii\web\View */
/* @var $model app\models\TransactionTemplate */

$searchModel = new TransactionTemplatePostingSearch();
$dataProvider = $searchModel->search(Yii::$app->request->queryParams,
    TransactionTemplatePosting::find()->ofTransactionTemplate($model)
);

$dataProvider->sort->defaultOrder = ['rank' => SORT_ASC];

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Back End'), 'url'=>['backend/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transaction Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="transaction-template-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php if ($model->getTransactionTemplatePostings()->count()==0): ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
        <?php endif ?>
        <?= Html::a(Yii::t('app', 'Clone'), ['clone', 'id' => $model->id], [
            'class' => 'btn btn-info',
            'data' => [
                'method' => 'post',
            ],
        ]) ?>       
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'organizational_unit_id',
            'status',
            'rank',
            'title',
            'description',
            'o_title',
            'o_description',
        ],
    ]) ?>

</div>

<?= $this->render('/transaction-template-postings/index', [
    'searchModel' => $searchModel,
    'dataProvider' => $dataProvider,
    'model'=>$model,
]); 
?>

