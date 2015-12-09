<?php

namespace app\controllers;

use app\models\Luck;
use Workerman\Memcache\Memcache;

class ActivityController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
