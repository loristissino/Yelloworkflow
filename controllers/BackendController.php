<?php

namespace app\controllers;

use app\components\CController;

class BackendController extends CController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
