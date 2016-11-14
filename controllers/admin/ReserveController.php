<?php
/**
 * Created by PhpStorm.
 * User: yeyun
 * Date: 16-9-10
 * Time: 下午3:19
 */

namespace app\controllers\admin;


use app\models\Reserve;
use yii\web\NotFoundHttpException;

class ReserveController extends BaseController
{

    public function actionIndex(){
        $model = Reserve::find()->all();
        return $this->render('/admin/reserve/index',[
            'model'=>$model
        ]);
    }


    /**
     * Form 表单
     * @return string
     */
    public function actionForm(){
        if(\Yii::$app->request->isPost){
            $type = \Yii::$app->request->post('type');
            $id = \Yii::$app->request->post('id');
            $model = new Reserve();
            if($id){
                $model = Reserve::findOne(['id'=>$id]);
            }
            return $this->renderAjax('/admin/reserve/_form',['type'=>$type,'model'=>$model]);
        }
    }


    /*
     * 表单提交
     * */
    public function actionSave(){
        if(\Yii::$app->request->isPost){

            $model = new Reserve();
            $reserve = $model->addReserve();
            if($reserve){
                return json_encode([
                    'state'=>true,
                    'type'=>'add',
                    'data'=>$reserve->attributes,
                    'html'=>$this->renderAjax('/admin/reserve/_list',['model'=>$reserve])
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
            $menu = \Yii::$app->request->post('Reserve');
            $model = new Reserve();
            $model = $model->updateData($menu['id']);
            if($model){
                return json_encode([
                    'state'=>true,
                    'type'=>'update',
                    'data'=>$model->attributes,
                    'html'=>$this->renderAjax('/admin/reserve/_list',['model'=>$model])
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

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Reserve::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}