<?php
/**
 * Created by PhpStorm.
 * User: Cloud
 * Date: 2018/3/1
 * Time: 15:16
 */

namespace app\commands;


use app\components\GrabBjSsc;
use yii\console\Controller;

class TwsscController extends Controller
{

    public function actionIndex(){
        new GrabBjSsc();
    }

}