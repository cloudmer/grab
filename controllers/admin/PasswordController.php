<?php

namespace app\controllers\admin;

use app\models\User;

class PasswordController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }


    public function actionUpdate(){
        if(\Yii::$app->request->isPost){
            $password = \Yii::$app->request->post('password');
            $newPassword = \Yii::$app->request->post('newPassword');
            $confirmPassword = \Yii::$app->request->post('confirmPassword');
            if($newPassword != $confirmPassword){
                return $this->render('index',['error'=>'两次密码不一致']);
            }
            $model = User::findOne(['id'=>\Yii::$app->user->identity->id]);
            if($model->password != md5($password)){
                return $this->render('index',['error'=>'原密码错误']);
            }

            $model->password = md5($newPassword);
            if($model->save()){
                return $this->render('index',['success'=>'修改成功']);
            }
        }
    }
}
