<?php
/**
 * Created by PhpStorm.
 * User: Cloud
 * Date: 2017/12/8
 * Time: 17:10
 */

namespace app\commands;


use app\components\GrabXjSsc;
use yii\console\Controller;

class XjsscController extends Controller
{

    /**
     * 新疆时时彩抓取
     */
    public function actionIndex(){
        new GrabXjSsc();
    }

}