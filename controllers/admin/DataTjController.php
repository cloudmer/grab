<?php
/**
 * Created by PhpStorm.
 * User: yeyun
 * Date: 16-9-5
 * Time: 下午11:05
 */

namespace app\controllers\admin;
use app\models\Comparison;


class DataTjController extends BaseController
{

    public function actionIndex()
    {
        $model = Comparison::findOne(['type'=>3]);
        return $this->render('index',['data'=>$model]);
    }

}