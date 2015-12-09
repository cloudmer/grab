<?php

namespace app\controllers\admin;
use yii\filters\AccessControl;
use app\models\User;

class ManageController extends BaseController
{
    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'matchCallback' => function ($rule, $action) {
                            // 回调函数 判断用户是否已经登陆
                            if(!\Yii::$app->user->isGuest && \Yii::$app->user->identity->role == User::ROLE_ADMIN){
                                return true;
                            }
                            return $this->redirect('/admin/login/logout');
                        }
                    ]
                ],
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index',[
            'systemInfo'=>$this->systemInfo(),
        ]);
    }

}
