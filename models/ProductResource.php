<?php

namespace app\models;

use Yii;
use yii\web\Link; // represents a link object as defined in JSON Hypermedia API Language.
use yii\web\Linkable;
use yii\helpers\Url;

/**
 * This is the model class for the REST resource associated to Product.
 */
class ProductResource extends Product implements Linkable
{
	// https://www.yiiframework.com/doc/guide/2.0/en/rest-resources
	public function fields()
	{
 		$fields = parent::fields();
        
        unset ($fields['created_at']);
        unset ($fields['updated_at']);
		return $fields;
	}

	/*
	public function extraFields()
	{
		return ['owner'];
	}
	*/
    
    public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['products/view', 'id' => $this->id], true),
        ];
    }
}
