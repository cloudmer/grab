<?php

namespace app\controllers\admin;
use app\components\Mem;
use yii\filters\AccessControl;
use app\models\User;

class ChatRoomController extends BaseController
{

    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [ //允许认证用户来访问注销 ‘logout’ 动作。@ 字符是另一个特殊标识用来表示认证用户。
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
        return $this->render('/admin/chat-room/index');
    }

    public function actionHeadPortrait(){
        if(\Yii::$app->request->isPost){
            $model = new User();
            return $model->userInfo('head_portrait');
        }
    }

}
