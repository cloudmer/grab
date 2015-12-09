<?php

namespace app\controllers\admin;

use app\components\Mem;
use app\models\LoginForm;
use app\models\User;
use yii\filters\AccessControl;

class LoginController extends BaseController
{
    public $layout = false;

    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','login','logout'],
                'rules' => [
                    [ //允许所有访客 guest （尚未认证的）访问登录’login’ 和 登陆页面’index’ 动作。roles 选项包含一个问号 ? ，这是一个用来表示“guests”的特殊符号。
                        'allow' => true,
                        'actions' => ['index','login'],
//                        'roles' => ['?'],
                        'matchCallback' => function ($rule, $action) {
                            // 回调函数 判断用户是否已经登陆
                            if(\Yii::$app->user->isGuest){
                                return true;
                            }
                            return $this->redirect('/admin/manage');
                        }
                    ],
                    [ //允许认证用户来访问注销 ‘logout’ 动作。@ 字符是另一个特殊标识用来表示认证用户。
                        'allow' => true,
                        'actions' => ['logout'],
                        'roles' => ['@']
                    ]
                ],
            ]
        ];
    }

    public function actionIndex()
    {
        $model = new LoginForm();
        return $this->render('/admin/login',['model'=>$model]);
    }

    public function actionLogin(){
        if (!\Yii::$app->user->isGuest) {
//            return $this->goHome();
            return $this->redirect('/admin/manage');
        }

        $model = new LoginForm();
        if($model->load(\Yii::$app->request->post()) && $model->login()){
            User::updateLoginInfo($this->getIp());
            return $this->redirect('/admin/manage');
        }else{
            return $this->render('/admin/login', [
                'model' => $model,
                'msg'=>$model->getErrors(),
            ]);
        }
    }

    public function actionLogout()
    {
        $mem = new Mem();
        $mem->delUserInfo();
        \Yii::$app->user->logout();
        return $this->redirect('/admin/login');
    }

}
