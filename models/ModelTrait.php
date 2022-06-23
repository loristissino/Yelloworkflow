<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

trait ModelTrait
{
    public $ternarianValues = [
        0 => 'Forbidden',
        1 => 'Required',
        2 => 'Allowed',
    ];
    
    public function getBooleanRepresentation($value)
    {
        return $value ? Html::tag('span', '', ['class'=>'glyphicon glyphicon-ok']) : '';
    }
    
    public function getTernarianRepresentation($value, $icons=['glyphicon-ok', 'glyphicon-asterisk'])
    {
        // the standard meaning is as follows: 1 means true, 0 means false, every other value "means" possible 
        return $value == 1 ?
            Html::tag('span', '', ['class'=>'glyphicon ' . $icons[0], 'title'=>$this->ternarianValues[$value]])
            :
            ($value == 0 ?
                ''
                :
                 Html::tag('span', '', ['class'=>'glyphicon ' . $icons[1], 'title'=>$this->ternarianValues[$value]])
            );
    }

    public function getTernarianDropdown($form, $options=[])
    {
        $options = array_merge([
            'field_name' => 'field_id',
            'array' => $this->ternarianValues,
        ], $options);
        return $form
            ->field($this, $options['field_name'])
            ->dropDownList($options['array'])
            ;
    }
    
    public function getTernarianRadioList($form, $options=[])
    {
        $options = array_merge([
            'field_name' => 'field_id',
            'array' => $this->ternarianValues,
        ], $options);
        return $form
            ->field($this, $options['field_name'])
            ->radioList($options['array'])
            ;
    }

    public function importSettings($settings=[]) {
        foreach($settings as $key=>$value) {
            $this->$key = $value;
        }
        return $this;
    }
}
