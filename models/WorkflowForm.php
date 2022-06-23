<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\components\LogHelper;
use \raoul2000\workflow\base\SimpleWorkflowBehavior;

class WorkflowForm extends Model
{
    public $model;
    public $id;
    public $status;
    public $oldStatus;
    public $description;
    public $object;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['model', 'id', 'status', 'description'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'model' => Yii::t('app', 'Model'),
            'id' => Yii::t('app', 'Id'),
            'status' => Yii::t('app', 'Status'),
            'description' => Yii::t('app', 'Description'),
        ];
    }
    
    public function save()
    {
        if ($this->oldStatus==$this->status) {
            return false;
        }
        $this->object->detachBehavior('workflowBehavior'); // we must disable the checks that are normally performed
        $this->object->wf_status = $this->status;
        try {
            $this->object->save(false);
            LogHelper::log($this->status, $this->object, ['without-notifications'=>true, 'change_description'=>$this->description]);
            return true;
        }
        catch (Exception $e) {
            return false;
        }
    }
    
}
