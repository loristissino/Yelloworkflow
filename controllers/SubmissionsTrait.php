<?php

namespace app\controllers;

use app\models\OrganizationalUnit;

trait SubmissionsTrait
{
    protected $organizationalUnit;

    public function setOrganizationalUnit()
    {
        $_organizationalUnitId = \Yii::$app->session->get('organizational_unit_id', null);
        
        if (!$_organizationalUnitId) {
            // if it is not set, we redirect to choice
            $this->redirect(['/site/choose-organizational-unit', 'return'=>\Yii::$app->request->url]);
            return;
        }
        
        $this->organizationalUnit = OrganizationalUnit::find()->withId($_organizationalUnitId)->withUser(\Yii::$app->user->identity->id)->active()->one();
    }    
}
