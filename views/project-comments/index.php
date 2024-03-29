<?php

use yii\helpers\Html;
use yii\grid\GridView;

$title = 'Comments';
$showCreateButton = true;

if (isset($is_thread) and $is_thread){
    $title = 'Thread';
    $showCreateButton = false;
} else {
    $is_thread = false;
}

$is_commentable = in_array($project->getWorkflowStatus()->getId(), [
    'ProjectWorkflow/draft',
    'ProjectWorkflow/submitted',
    'ProjectWorkflow/questioned',
    'ProjectWorkflow/approved',
    'ProjectWorkflow/ended',
    'ProjectWorkflow/reimbursed',
]);

$columns = [
    //'project_id',
    [
        'attribute'=>'updated_at',
        'format'=>'raw',
        'value'=>function($data) {
            return sprintf('%s %s', Yii::$app->formatter->asDate($data['updated_at']), Yii::$app->formatter->asTime($data['updated_at']));
        },
    ],
    [
        'attribute'=>'User',
        'format'=>'raw',
        'value'=>'user.fullName',
    ],
    'comment:ntext',
];

if ($is_commentable) {
    $columns[] = 
            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{update} {delete} {reply}',
				'buttons'=>[
					'delete' => function ($url, $model) use ($is_thread) {
						return (!$is_thread and $model->isUpdateable) ? Html::a('<span class="glyphicon glyphicon-trash"></span>', [
                            'project-comments/delete', 'id'=>$model->id, 'controller'=>Yii::$app->controller->id,
                            ],
                            [
								'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
								'data-method' => 'post',
                                'title' => Yii::t('yii', 'Delete')
							]) : null;
						},
					'update' => function ($url, $model) use ($is_thread) {
						return (!$is_thread and $model->isUpdateable) ? Html::a('<span class="glyphicon glyphicon-pencil"></span>', [
                            'project-comments/update', 'id'=>$model->id, 'controller'=>Yii::$app->controller->id
                            ], [
                            'title' => Yii::t('yii', 'Update')
                            ]) : null;
						},
					'reply' => function ($url, $model) use ($project, $is_thread) {
						return !$is_thread ? Html::a('<span class="glyphicon glyphicon-share-alt"></span>', [
                            'project-comments/create', 'reply_to'=>$model->id, 'project'=>$project->id, 'controller'=>Yii::$app->controller->id
                            ], [
                                 'title' => Yii::t('yii', 'Reply')
                            ]): null;
						},
                    ]
            ]
            ;
}

?>

<div class="project-comment-index">

    <h2><?= Yii::t('app', $title) ?></h2>

    <?php if($showCreateButton and $is_commentable): ?>
        <p>
            <?= Html::a(Yii::t('app', 'Create Project Comment'), [
                'project-comments/create', 'project'=>$project->id, 'controller'=>Yii::$app->controller->id
            ], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif ?>

    <?php if ($dataProvider->count > 0): ?>
    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $columns
    ]); ?>
    
    <?php else: ?>
    
    <?= Yii::t('app', 'No comments.') ?>    

    <?php endif ?>

</div>
