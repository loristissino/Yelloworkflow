<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\behaviors\TimestampBehavior;
use \raoul2000\workflow\base\SimpleWorkflowBehavior;

trait WorkflowTrait
{
    public $workflowError = 'Unspecified error.';
    
    public function getViewLinkCode($property, $controller, $options=[])
    {
        return Yii\helpers\Html::a($this->$property, [$controller . '/view', 'id'=>$this->id], $options);
    }

    public function beforeDelete()
    {
        \app\components\LogHelper::log('deleted', $this);
        return parent::beforeDelete();
    }

    public function beforeSave($insert)
    {
        if (!$this->organizational_unit_id) {
            $this->organizational_unit_id = Yii::$app->session->get('organizational_unit_id');
        }
        if ($insert) {
            $this->enterWorkflow();
        }

        return parent::beforeSave($insert);
    }    
    
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            \app\components\LogHelper::log('created', $this);
        }
        // the workflow manager takes care of other relevant changes
        return parent::afterSave($insert, $changedAttributes);
    }
    
    public function getWorkflowLabel()
    {
        return Html::tag('span', Yii::t(Yii::t('app', get_class($this)), $this->getWorkflowStatus()->getLabel()), ['style'=>'color: ' . $this->getWorkflowStatus()->getMetadata('color')]);
    }

    public function getWorkflowIcon()
    {
        return $this->getWorkflowStatus()->getMetaData('icon', '❓');
    }
    
    public function getAuthorizedTransitions()
    {
        $transitions = [];
        
        foreach ($this->getWorkflowStatus()->getTransitions() as $t) {
            $permission = $t->getEndStatus()->getMetadata('permission', false);
            $limit = $t->getEndStatus()->getMetadata('limit', '');
            if (!$permission or Yii::$app->user->hasAuthorizationFor($permission) and ($limit=='ou' ? $this->organizationalUnit->hasLoggedInUser() : true)) {
                $transitions[] = $t;
            }
        }
        return $transitions;
    }

    public function getAuthorizedTransitionsIds()
    {
        return array_map(function($t) {
            return $t->getEndStatus()->getid();
            }, $this->getAuthorizedTransitions()
        );
    }
    
    public function canBeSentToStatus($status)
    {
        return in_array($status, $this->getAuthorizedTransitionsIds());
    }
    
    public function init()
    {
        $this->on('beforeChangeStatusFrom*', function($event) {
            if (!in_array($event->getEndStatus()->getId(), $this->getAuthorizedTransitionsIds())) {
                $this->workflowError = 'You do not have the authorization for this workflow status change.';
                $event->invalidate($this->workflowError);
            }
            $this->_runWorkflowChecks($event);
        });
        $this->on('afterChangeStatusFrom*', function($event) {
            $this->_runWorkflowRoutines($event);
        });
    }
    
    public function getLastLoggedActivity() 
    {
        return $this->getLoggedActivities()->orderBy(['happened_at'=>SORT_DESC])->one();
    }

    public function getLastLoggedActivityTime() 
    {
        $a = $this->getLastLoggedActivity();
        return $a ? $a->happened_at : null;
    }
    
    public function getAttachmentsAreDeletable()
    {
        return substr($this->getWorkflowStatus()->getId(), -6) == '/draft';
    }
    
    public function getLoggedActivities()
    {
        return Activity::find()->withModel($this::className())->withModelId($this->id);
    }
    
}
