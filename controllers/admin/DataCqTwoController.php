<?php
/**
 * Created by PhpStorm.
 * User: yeyun
 * Date: 16-9-13
 * Time: 下午4:01
 */

namespace app\controllers\admin;


use app\models\Comparison;

class DataCqTwoController extends BaseController
{

    public function actionIndex(){
        $model = Comparison::findOne(['type'=>22]);
        return $this->render('index',['data'=>$model]);
    }

}