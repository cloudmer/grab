<?php

namespace app\controllers\admin;

use app\models\Menus;
use app\models\User;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

class SettingsController extends BaseController
{

    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','menus','form','save','update','delete','show'],
                'rules' => [
                    [ //允许认证用户来访问注销 ‘logout’ 动作。@ 字符是另一个特殊标识用来表示认证用户。
                        'allow' => true,
                        'actions' => ['index','menus','form','save','update','delete','show'],
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
        $model = Menus::menuSub(1,false);
        return $this->render('/admin/settings/menu/index',[
            'model'=>$model
        ]);
    }

    public function actionMenus(){
        $model = Menus::menuSub(1,false);
        return $this->render('/admin/settings/menu/index',[
            'model'=>$model
        ]);
    }

    /*
     * Form 表单
     * */
    public function actionForm(){
        if(\Yii::$app->request->isPost){
            $type = \Yii::$app->request->post('type');
            $id = \Yii::$app->request->post('id');
            $model = new Menus();
            if($id){
                $model = Menus::findOne(['id'=>$id]);
                !$model->father_id2 && !$model->father_id2 ? $type = 1 : null;
                $model->father_id2 ? $type = 2 : null;
                $model->father_id3 ? $type = 3 : null;
            }
            return $this->renderAjax('/admin/settings/menu/_form',['type'=>$type,'model'=>$model]);
        }
    }

    /*
     * 表单提交
     * */
    public function actionSave(){
        if(\Yii::$app->request->isPost){
            $model = new Menus();
            $menu = $model->addMenu();
            if($menu){
                return json_encode([
                    'state'=>true,
                    'type'=>'add',
                    'data'=>$menu->attributes,
                    'html'=>$this->renderAjax('/admin/settings/menu/menus',['model'=>$menu])
                ]);
            }
            return json_encode(['state'=>false]);
        }
    }

    /*
     * 修改 菜单栏信息
     * */
    public function actionUpdate(){
        if(\Yii::$app->request->isPost){
            $menu = \Yii::$app->request->post('Menus');
            $model = new Menus();
            $model = $model->updateData($menu['id']);
            if($model){
                return json_encode([
                    'state'=>true,
                    'type'=>'update',
                    'data'=>$model->attributes,
                    'html'=>$this->renderAjax('/admin/settings/menu/menus',['model'=>$model])
                ]);
            }
            return json_encode(['state'=>false]);
        }
    }

    /*
     * 删除 菜单栏目
     * */
    public function actionDelete(){
        if(\Yii::$app->request->isPost){
            $model = $this->findModel(\Yii::$app->request->post('id'));
            return $model->deleteData($model);
        }
    }

    /*
     * 菜单栏目 显示 隐藏
     * */
    public function actionShow(){
        if(\Yii::$app->request->isPost){
            $model = $this->findModel(\Yii::$app->request->post('id'));
            return $model->is_show($model);
        }
    }


    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menus::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
