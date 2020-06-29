<?php

namespace app\models;

use Yii;
use yii\web\Link; // represents a link object as defined in JSON Hypermedia API Language.
use yii\web\Linkable;
use yii\helpers\Url;

/**
 * This is the model class for the REST resource associated to Transaction.
 */
class TransactionResource extends Transaction implements Linkable
{
	// https://www.yiiframework.com/doc/guide/2.0/en/rest-resources
	public function fields()
	{
 		$fields = parent::fields();
		// remove fields that contain sensitive information
		// unset($fields['owner_id']);
		// unset($fields['id']);
        
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
		/*
		Yii::$app->urlManager->rules = [
			'<controller:[\w-]+>/<id:\d+>' => '<controller>/view',
		];
		*/
        return [
            Link::REL_SELF => Url::to(['transactions/view', 'id' => $this->id], true),
        ];
    }
}
