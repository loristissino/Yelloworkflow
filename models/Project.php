<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\behaviors\TimestampBehavior;
use \raoul2000\workflow\base\SimpleWorkflowBehavior;
/**
 * This is the model class for table "projects".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string|null $co_hosts
 * @property string|null $partners
 * @property string $period
 * @property string $place
 * @property string $wf_status
 * @property int $organizational_unit_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property PlannedExpense[] $plannedExpenses
 * @property ProjectComment[] $projectComments
 * @property OrganizationalUnit $organizationalUnit
 */
class Project extends \yii\db\ActiveRecord
{
    private $_recentCommentsCache = '';
    
    use WorkflowTrait;
    use ModelTrait;
    
    //public $workflowError = 'Unspecified error.';
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'projects';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'workflowBehavior' => [
                'class'                    => SimpleWorkflowBehavior::className(),
                'statusAttribute'          => 'wf_status',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'period', 'place'], 'required'],
            [['description', 'co_hosts', 'partners'], 'string'],
            [['organizational_unit_id', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 100],
            [['period', 'place'], 'string', 'max' => 255],
            [['title', 'description', 'period', 'place', 'co_hosts', 'partners'], 'filter', 'filter'=>function($value) {return trim(strip_tags($value));}],
            [['wf_status'], 'string', 'max' => 40],
            [['organizational_unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrganizationalUnit::className(), 'targetAttribute' => ['organizational_unit_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'co_hosts' => Yii::t('app', 'Co Hosts'),
            'partners' => Yii::t('app', 'Partners'),
            'period' => Yii::t('app', 'Period'),
            'place' => Yii::t('app', 'Place'),
            'wf_status' => Yii::t('app', 'Workflow Status'),
            'organizational_unit_id' => Yii::t('app', 'Organizational Unit'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[PlannedExpenses]].
     *
     * @return \yii\db\ActiveQuery|PlannedExpenseQuery
     */
    public function getPlannedExpenses()
    {
        return $this->hasMany(PlannedExpense::className(), ['project_id' => 'id']);
    }

    /**
     * Gets query for [[ProjectComments]].
     *
     * @return \yii\db\ActiveQuery|ProjectCommentQuery
     */
    public function getProjectComments()
    {
        return $this->hasMany(ProjectComment::className(), ['project_id' => 'id']);
    }

    /**
     * Gets query for [[OrganizationalUnit]].
     *
     * @return \yii\db\ActiveQuery|OrganizationalUnitQuery
     */
    public function getOrganizationalUnit()
    {
        return $this->hasOne(OrganizationalUnit::className(), ['id' => 'organizational_unit_id']);
    }
    
    public function getIsDraft()
    {
        return $this->hasWfStatus('draft');
    }
    
    public function getViewLink($options=[])
    {
        return $this->getViewLinkCode('title', 'projects-management', $options);
    }

    public function getSubmitterViewLink($options=[])
    {
        return $this->getViewLinkCode('title', 'project-submissions', $options);
        // this link is used for views from the submitter PoV
    }
    
    public function __toString()
    {
        return $this->title;
    }
    
    public function hasWfStatus($status)
    {
        return $this->getWorkflowStatus()->getId() == 'ProjectWorkflow/' . $status;
    }
    
    public function getIsApproved()
    {
        return $this->hasWfStatus('approved') or $this->hasWfStatus('partially-approved');
    }
    
    public static function getProjectsAsArray($order_by, $organizational_unit_id, $also_ended=false)
    {
        return
          self::find()
            ->approved($also_ended)
            ->withOrganizationalUnitId($organizational_unit_id)
            ->orderBy($order_by)
            ->select(['title'])
            ->indexBy('id')
            ->column()
            ;
    }

    public static function getDropdown($form, $model, $options=[])
    {
        $options = array_merge([
            'field_name' => 'project_id',
            'prompt' => 'Choose the project',
            'order_by' => ['created_at' => SORT_DESC],
            'organizational_unit_id' => 0,
            'alsoEnded' => false,
        ], $options);
        
        return $form
            ->field($model, $options['field_name'])
            ->dropDownList(
                self::getProjectsAsArray($options['order_by'], $options['organizational_unit_id'], $options['alsoEnded']),
                [
                    'prompt'=>Yii::t('app', $options['prompt']),
                ]
            );
    }

    public function cloneModel($organizationalUnitId)
    {
        $model = new Project();
        $model->attributes = $this->attributes;
        $model->title .= ' - ' . Yii::t('app', '(Copy)');
        $model->id = null;
        $model->organizational_unit_id = $organizationalUnitId;
        $model->created_at = null;
        $model->updated_at = null;
        $model->sendToStatus(null);
        $model->save();
        
        foreach($this->plannedExpenses as $item) {
            $newItem = new PlannedExpense();
            $newItem->attributes = $item->attributes;
            $newItem->id = null;
            $newItem->project_id = $model->id;
            $newItem->save();
        }
    
        return $model;
    }
    
    public function getMissingExpenses()
    {
        return Posting::find()->joinWith('transaction')->joinWith('account')->withRealAccount(false)->relatedToProject($this)->count() == 0;
    }    

    public function getAllowsComments()
    {
        return $this->getLoggedActivities()->count() > 1;
    }

    private function _runWorkflowChecks($event)
    {
        $this->cacheRecentComments();
        switch ($event->getEndStatus()->getId()) {
            case 'ProjectWorkflow/submitted':
                if (sizeof($this->getPlannedExpenses()->all())==0) {
                    $this->workflowError = 'Projects must have at least one planned expense.';
                    $event->invalidate($this->workflowError);
                }
                if (!$this->organizationalUnit->hasLoggedInUser()) {
                    $this->workflowError = 'Only members of the organizational unit can submit the project.';
                    $event->invalidate($this->workflowError);
                }
                if ($this->getWorkflowStatus()->getId()=='ProjectWorkflow/questioned' and !$this->_hasRecentComments()) {
                    $this->workflowError = 'To re-submit a questioned project at least one comment is needed.';
                    $event->invalidate($this->workflowError);
                }
                break;

            case 'ProjectWorkflow/partially-approved':
                if ( ! $this->_hasRecentComments() ) {
                    $this->workflowError = 'To partially approve a project at least one comment is needed.';
                    $event->invalidate($this->workflowError);
                }
                break;

            case 'ProjectWorkflow/rejected':
                if ( ! $this->_hasRecentComments() ) {
                    $this->workflowError = 'To reject a project at least one comment is needed.';
                    $event->invalidate($this->workflowError);
                }
                break;

            case 'ProjectWorkflow/questioned':
                if ( ! $this->_hasRecentComments() ) {
                    $this->workflowError = 'To question a project at least one comment is needed.';
                    $event->invalidate($this->workflowError);
                }
                break;
                
            case 'ProjectWorkflow/ended':
                if ( ! $this->_allDeclaredExpensesAreRecorded() ) {
                    $this->workflowError = 'Not all the paid expenses have been recorded.';
                    $event->invalidate($this->workflowError);
                }
                break;
                
        }
        
    }

    private function _runWorkflowRoutines($event)
    {
        $log = "Running workflow routines...\n";
        
        $log .= $event->getTransition()->getId() . "\n";
        
        $options = ['related'=>['frozenPlannedExpenses']];

        if ($event->getEndStatus()->getId() == 'ProjectWorkflow/draft') {
             Yii::$app->session->setFlash('info', Yii::t('app', 'The project must be submitted again after the edits.'));
        }
        
        if ($event->getEndStatus()->getId() == 'ProjectWorkflow/submitted') {
            $options['related'] = ['plannedExpenses'];
        }

        if (in_array($event->getEndStatus()->getId(), ['ProjectWorkflow/approved','ProjectWorkflow/partially-approved'])) {
            $ou = $this->getOrganizationalUnit()->one();
            if (! $ou->hasOwnCash) {
                $periodicalReport = new \app\models\PeriodicalReport();
                $periodicalReport->begin_date = date('Y-m-d');
                $periodicalReport->end_date = date('Y-m-d', time()+365*24*60*60);
                $periodicalReport->organizational_unit_id = $ou->id;
                $periodicalReport->name = Yii::t('app', 'Expenses for «{title}»', ['title' => $this->title]);
                $periodicalReport->save();
            }
            $this->_createCommentAfterApproval();
        }

        if ($event->getEndStatus()->getId() == 'ProjectWorkflow/rejected') {
            $options['related'] = ['projectComments'];
        }

        if ($event->getEndStatus()->getId() == 'ProjectWorkflow/ended') {
            $options['related'] = ['projectComments', 'allRelatedExpenses'];
        }

        if ($event->getEndStatus()->getId() == 'ProjectWorkflow/reimbursed') {
            $this->_markReimbursedAllExpenses();
            $options['related'] = ['projectComments', 'allRelatedExpenses'];
        }
        
        \app\components\LogHelper::log($event->getEndStatus()->getId(), $this, $options);
        
        //file_put_contents(sprintf("log_project_routines_%s.txt", date("His")), $log);
    }

    private function _hasRecentComments()
    {
        return sizeof($this->getProjectComments()->after($this->LastLoggedActivityTime)->ofUser(Yii::$app->user->identity->id)->all()) > 0;
    }
    
    public function cacheRecentComments()
    {
        $this->_recentCommentsCache = $this->getProjectComments()->after($this->LastLoggedActivityTime)->ofUser(Yii::$app->user->identity->id)->all();
    }
    
    public function getLastComments()
    {
        // To save comments before a workflow state transition, it is recommended to call the method cacheRecentComments() before calling this one
        if (!$this->_recentCommentsCache) {
            $this->cacheRecentComments();
        }
        $comments = $this->_recentCommentsCache;
        
        $text = '';
        foreach($comments as $comment) {
            $text .= Yii::t('app', "{comment}\n\n", ['comment'=>$comment->comment]);
        }
        
        return $text;
    }
    
    public function getLastCommentsWithoutGTSign()
    {
        // this can be helpful in generated notifications, where we don't want '>' signs to be interpreted with the markdown meaning
        return str_replace('>', '›', $this->getLastComments());
    }

    public function getAllRelatedExpenses()
    {
        $query = Posting::find()->withRealAccount(false)->relatedToProject($this)->notWithTransactionStatus('TransactionWorkflow/rejected');
        
        $query
        ->joinWith('account')
        ->joinWith('transaction')
        ;
        
        return $query;
    }
    
    public function getFrozenPlannedExpenses()
    {
        $items = [];
        foreach($this->getPlannedExpenses()->all() as $expense){
            $text = Yii::t('app', '[{type}] {description}: {amount}', [
                'type'=>$expense->expenseType->name,
                'description'=>$expense->description,
                'amount'=>Yii::$app->formatter->asCurrency($expense->amount),
            ]);
            
            if ($expense->notes) {
                $text .= Yii::t('app', ' -- {notes}', [
                    'notes'=>$expense->notes
                ]);
            }
            
            $items[] = $text;
        }
        return $items;
    }
    
    public function getFrozenPlannedExpensesAsText()
    {
        return '* ' . join("\n* ", $this->getFrozenPlannedExpenses());
    }
    
    private function _createCommentAfterApproval()
    {
        $comment = new ProjectComment();
        $comment->user_id = Yii::$app->user->identity->id;
        $comment->project_id = $this->id;
        $comment->comment = Yii::t('app', '[Autogenerated comment]') . ' - '. Yii::t('app', 'Submitted planned expenses') . "\n\n" . $this->getFrozenPlannedExpensesAsText();
        $comment->save();
        return $comment->id;
    }
    
    private function _allDeclaredExpensesAreRecorded()
    {
        $query = $this->getAllRelatedExpenses();
                
        return 
            $query->count()
            ===
            $query->withTransactionStatus('TransactionWorkflow/recorded')->count()
        ;
    }

    private function _markReimbursedAllExpenses()
    {
        foreach($this->getAllRelatedExpenses()->withTransactionStatus('TransactionWorkflow/recorded')->all() as $posting) {
            $posting->transaction->sendToStatus('reimbursed');
            $posting->transaction->save();
        }
    }
    
    /**
     * {@inheritdoc}
     * @return ProjectQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProjectQuery(get_called_class());
    }
}
