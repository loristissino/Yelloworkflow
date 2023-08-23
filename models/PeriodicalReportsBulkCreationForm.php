<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\OrganizationalUnit;
use yii\helpers\ArrayHelper;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class PeriodicalReportsBulkCreationForm extends Model
{
    public $organizational_unit_ids;
    public $name;
    public $begin_date;
    public $end_date;
    public $due_date;
    public $required_attachments;

    public $organizationalUnits = [];

    public function init()
    {
        $this->organizationalUnits = OrganizationalUnit::find()
        ->active()
        ->withPossibileActions(OrganizationalUnit::HAS_OWN_CASH)
        ->select(['name'])
        ->indexBy('id')
        ->orderBy(['possible_actions'=>SORT_DESC, 'rank'=>SORT_ASC, 'name'=>SORT_ASC])
        ->column();
        
        
        $begin = mktime(0, 0, 0, getdate()['mday']>15 ? getdate()['mon']+1: getdate()['mon'], 1, getdate()['year']);
        $this->begin_date = date('Y-m-d', $begin); // first day of month
        $this->end_date = date('Y-m-d', mktime(0, 0, 0, getdate()['mday']>15 ? getdate()['mon']+2: getdate()['mon']+1, 0, getdate()['year'])); // last day of month
        $this->due_date = date('Y-m-d', mktime(0, 0, 0, getdate()['mday']>15 ? getdate()['mon']+2: getdate()['mon']+1, 5, getdate()['year'])); // five days after end of month

        $this->name = Yii::t('app', 'Periodical Report') . ' - ' . Yii::t('app', date('F', $begin)) . ' ' . date('Y', $begin);
                
        $this->required_attachments = Yii::$app->params['periodicalReports']['requiredAttachments'];
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name', 'begin_date', 'end_date', 'due_date'], 'required'],
            [['begin_date', 'end_date'], 'safe'],
            [['required_attachments'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'organizational_unit_ids' => Yii::t('app', 'Organizational Units'),
            'name' => Yii::t('app', 'Name'),
            'begin_date' => Yii::t('app', 'Begin Date'),
            'end_date' => Yii::t('app', 'End Date'),
            'due_date' => Yii::t('app', 'Due Date'),
            'required_attachments' => Yii::t('app', 'Required Attachments'),
        ];
    }
    
    public function save()
    {
        $count = 0;
        $data = ArrayHelper::getValue($_POST, 'PeriodicalReportsBulkCreationForm', []);
        $ids = ArrayHelper::getValue($data,  'organizational_unit_ids', []);
        if (is_array($ids)) {
            foreach(ArrayHelper::getValue($_POST['PeriodicalReportsBulkCreationForm'], 'organizational_unit_ids', []) as $id) {
                try {
                    $periodicalReport = new PeriodicalReport();
                    $periodicalReport->attributes = $this->attributes;
                    $periodicalReport->organizational_unit_id = $id;
                    $periodicalReport->save();
                    $count++;
                }
                catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
        }
        if ($count==0) {
            $this->addError('organizational_unit_ids', 'At least one Organizational Unit must be selected.');
        }
        return $count;
    }
    
}
