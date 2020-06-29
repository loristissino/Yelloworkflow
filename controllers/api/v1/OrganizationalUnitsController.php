<?php

namespace app\controllers\api\v1;

use yii\data\ActiveDataProvider;
use app\components\RestController;
use app\models\OrganizationalUnitResource;

class OrganizationalUnitsController extends RestController
{
	public $modelClass = '\app\models\OrganizationalUnitResource';
	
	public function prepareDataProvider()
	{
		$query = OrganizationalUnitResource::find();

		$provider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => 1000,
			],
			'sort' => [
				'defaultOrder' => [
					'rank' => SORT_ASC, 
				]
			],
		]);
		
		return $provider;
	}
}


