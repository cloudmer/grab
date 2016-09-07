<?php
/**
 * Created by PhpStorm.
 * User: yeyun
 * Date: 16-9-5
 * Time: 下午11:09
 */

namespace app\controllers\admin;
use app\models\Comparison;


class DataXjController extends BaseController
{

    public function actionIndex()
    {
        $model = Comparison::findOne(['type'=>4]);
        return $this->render('index',['data'=>$model]);
    }

}