<?php
namespace app\components;
use yii\base\Widget;
use yii\helpers\Html;

class UnorderedListWidget extends Widget {
    public $introMessage = "A list of items:";
    public $noItemsMessage = "No items.";
    public $items = [];
    public $textProperty = 'title';
    public $key = 'id';
    public $link = '#';
    
    public function init() {
        parent::init();
    }
    public function run(){
        return $this->render('UnorderedList', [
            'introMessage' => $this->introMessage,
            'noItemsMessage' => $this->noItemsMessage,
            'items'=>$this->items,
            'textProperty'=>$this->textProperty,
            'key'=>$this->key,
            'link'=>$this->link,
        ]);
    }
}
