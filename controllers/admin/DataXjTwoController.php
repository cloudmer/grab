<?php
/**
 * Created by PhpStorm.
 * User: yeyun
 * Date: 16-9-13
 * Time: 下午4:02
 */

namespace app\controllers\admin;


use app\models\Comparison;

class DataXjTwoController extends BaseController
{

    public function actionIndex(){
        $model = Comparison::findOne(['type'=>44]);
        return $this->render('index',['data'=>$model]);
    }

}