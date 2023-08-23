<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\components\LogHelper;

class OrganizationalUnitsCeilingAmountsForm extends Model
{
    public $values;
    private $_count;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['values'], 'required'],
            [['values'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'values' => Yii::t('app', 'Values'),
        ];
    }
    
    public function loadValues()
    {
        $ous = OrganizationalUnit::find()->active()->withPossibileActions(7)->orderBy(['name'=>'SORT_ASC'])->all();
        $values = ["#Id\t#" . Yii::t('app', 'Amount') . "\t#" . Yii::t('app', 'Name')];
        foreach($ous as $ou) {
            $values[] = sprintf("%d\t%d\t%s", $ou->id, round($ou->ceiling_amount), $ou->name);
        }
        $this->values = join("\n", $values);
        return true;
    }
    
    public function save()
    {
        $updated = [];
        $lines = explode("\n", $this->values);
        if (sizeof($lines)>0) {
            foreach ($lines as $line) {
                if (substr($line, 0, 1)=='#') {
                    continue;
                }
                $fields = explode("\t", $line);
                if (sizeof($fields)>=2) {
                    $id = $fields[0];
                    $amount = $fields[1];
                    $ou = OrganizationalUnit::findOne($id);
                    if ($ou) {
                        if ($ou->ceiling_amount != $amount) {
                            $ou->ceiling_amount = $amount;
                            if ($ou->save()) {
                                $updated[] = $id;
                            }
                        }
                    }
                }
            }
        }
        $this->_count = sizeof($updated);
        return true;
    }
    
    public function getUpdatesCount() {
        return $this->_count;
    }
}
