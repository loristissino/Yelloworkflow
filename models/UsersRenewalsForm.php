<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\components\LogHelper;

class UsersRenewalsForm extends Model
{
    public $year;
    public $ids;
    private $_validatedIds;
    public $updated;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['year', 'ids'], 'required'],
            [['year'], 'number', 'min'=>2000, 'max'=>2100],
            ['ids', 'validateIds'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'year' => Yii::t('app', 'Year'),
            'ids' => Yii::t('app', 'External Ids'),
        ];
    }
    
    public function validateIds($attribute, $params)
    {
        $this->_validatedIds=[];
        $lines = explode("\n", $this->ids);
        foreach($lines as $index=>$line) {
            $value = trim($line);
            if($value=='')
                continue;
            if (!is_numeric($value)) {
                $this->addError($attribute, Yii::t('app', 'Incorrect value on line {line}.', ['line'=>$index+1]));
                return false;
            }
            $id = intval($value);
            if ($id!=$value) {
                $this->addError($attribute, Yii::t('app', 'Incorrect value on line {line}.', ['line'=>$index+1]));
                return false;
            }
            $this->_validatedIds[]=$id;
        }
    }
    
    public function save()
    {
        $this->updated = [];
        foreach($this->_validatedIds as $id) {
            $user = User::find()->where(['external_id' => $id])->one();
            if ($user && ($user->last_renewal != $this->year)) {
                $user->last_renewal = $this->year;
                $user->save(false);
                $this->updated[] = $user;
            }
        }
        return true;
    }
    
}
