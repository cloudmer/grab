<?php
/**
 * Created by PhpStorm.
 * User: Cloud
 * Date: 2017/12/8
 * Time: 17:10
 */

namespace app\commands;


use app\components\GrabCqSsc;
use yii\console\Controller;

class CqsscController extends Controller
{

    /**
     * 重庆时时彩抓取
     */
    public function actionIndex(){
        new GrabCqSsc();
    }

}