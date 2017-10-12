<?php
/**
 * Created by PhpStorm.
 * User: yeyun
 * Date: 17-10-12
 * Time: 下午5:49
 */

namespace app\controllers\admin;


use app\models\CustomPackage;
use Yii;

/**
 * 自定义包
 *
 * Class CustomPackageController
 * @package app\controllers\admin
 */
class CustomPackageController extends BaseController
{

    /**
     * 列表
     */
    public function actionIndex(){
        $model = CustomPackage::find()->all();
        return $this->render('index',['model'=>$model]);
    }

    /**
     * 表单
     */
    public function actionForm(){
        $model = new CustomPackage();
        return $this->render('_form',[
            'model' => $model
        ]);
    }

    /**
     * 编辑 添加
     */
    public function actionSubmit(){
        if(Yii::$app->request->post()){
            $post = Yii::$app->request->post('CustomPackage');
            $model = new CustomPackage();
            if($post['id']){
                $model = $model->find()->where(['id'=>$post['id']])->one();
                if($model->validate() && $model->save()){
                    //编辑
                    $this->redirect('/admin/custom-package/');
                }
            }

            $model->load(Yii::$app->request->post());
            if($model->validate() && $model->save()){
                //添加
                $this->redirect('/admin/custom-package/');
            }
        }
    }

    /**
     * 查看
     */
    public function actionSee(){
        $id = Yii::$app->request->get('id');
        $model = CustomPackage::find()->where(['id' => $id])->one();
        return $this->render('see',['model'=>$model]);
    }

    /**
     * 编辑
     */
    public function actionEdit(){
        $id = Yii::$app->request->get('id');
        $model = CustomPackage::find()->where(['id'=>$id])->one();

        return $this->render('_form',[
            'model' => $model
        ]);
    }

    /**
     * 删除数据包
     */
    public function actionDelete(){
        $id = Yii::$app->request->post('id');
        CustomPackage::deleteAll(['id'=>$id]);
        return json_encode(['state'=>true,'msg'=>'删除成功']);
    }

}