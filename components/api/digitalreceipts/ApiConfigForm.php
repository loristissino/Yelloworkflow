<?php 

namespace app\components\api\digitalreceipts;

use yii\base\Model;

class ApiConfigForm extends Model
{
    public $taxCode;
    public $password;
    public $pin;

    public function rules()
    {
        return [
            [['taxCode', 'password', 'pin'], 'required'],
            [['taxCode', 'password', 'pin'], 'string'],
        ];
    }
}
