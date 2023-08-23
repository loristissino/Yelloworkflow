<?php

namespace app\models;

use Yii;
use yii\base\Model;

class TransactionStatusesForm extends Model
{
    public $url;
    public $values;
    public $weight;
    public $weights;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['weights'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'url' => Yii::t('app', 'Url'),
            'weights' => Yii::t('app', 'Weights'),
        ];
    }
   
    public function loadValuesFromUserSettings()
    {
        $this->values = \app\models\Transaction::getWeightedStatuses();
        $this->weight = Yii::$app->user->identity->getPreference('transaction_statuses', 768);
        
        $this->weights = [];
        for ($i=1; $i<65536; $i*=2) {
            if ($this->weight & $i) {
                $this->weights[] = $i;
            }
        }
    }
        
    public function save()
    {
        $sum = $this->weights ? array_sum($this->weights) : 0;
        $user = User::findOne(\Yii::$app->user->id);
        $user->setPreference('transaction_statuses', $sum)->save(false);
        Yii::debug("saved" . $sum);
        return true;
    }
    
}
