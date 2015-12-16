<?php

namespace app\controllers\admin;
use app\models\Comparison;

class DataOldController extends BaseController
{
    public function actionIndex()
    {
        $model = Comparison::findOne(['type'=>2]);
        return $this->render('index',['data'=>$model]);
    }

}
