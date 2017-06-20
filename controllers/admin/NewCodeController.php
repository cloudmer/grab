<?php
/**
 * Created by PhpStorm.
 * User: yeyun
 * Date: 17-6-17
 * Time: 下午1:54
 */

namespace app\controllers\admin;
use app\models\Newcodedata;
use Yii;

class NewCodeController extends BaseController
{

    public function actionIndex(){
        $model = Newcodedata::find()->all();
        return $this->render('index', [
            'model' => $model
        ]);
    }

    public function actionForm(){
        $type = Yii::$app->request->get('type');
        $model = new Newcodedata();
        return $this->render('_form',[
            'type' => $type,
            'model' => $model
        ]);
    }

    public function actionSubmit(){
        if(Yii::$app->request->post()){
            $type = Yii::$app->request->post('type');
            $model = new Newcodedata();

            $post = Yii::$app->request->post('Newcodedata');
            if($post['id']){
                $model = $model->find()->where(['id'=>$post['id']])->one();
                $model->load(Yii::$app->request->post());
                if($model->validate() && $model->save()){
                    //编辑
                   return $this->redirect('/admin/new-code/index');
                }
            }

            //添加全部
            if($type == 0){
                $model->load(Yii::$app->request->post());
                for ($i=1; $i<=3; $i++){
                    $m = new Newcodedata();
                    $m->number        = Yii::$app->request->post('Newcodedata')['number'];
                    $m->alias         = Yii::$app->request->post('Newcodedata')['alias'];
                    $m->contents      = Yii::$app->request->post('Newcodedata')['contents'];
                    $m->start         = Yii::$app->request->post('Newcodedata')['start'];
                    $m->end           = Yii::$app->request->post('Newcodedata')['end'];
                    $m->status        = Yii::$app->request->post('Newcodedata')['status'];
                    $m->type          = $i;
                    $m->time          = time();
                    $m->save();
                }
                $this->redirect('/admin/new-code/index');
                return;
            }

            //单个添加
            $model->load(Yii::$app->request->post());
            $model->type = $type;
            $model->time = time();
            if($model->validate() && $model->save()){
                //添加
                $this->redirect('/admin/new-code/index');
            }
        }
    }


    /**
     * 查看数据包
     */
    public function actionSee(){
        $id = Yii::$app->request->get('id');
        $model = Newcodedata::find()->where(['id' => $id])->one();
        return $this->render('see',['model'=>$model]);
    }

    /**
     * 删除数据包
     */
    public function actionDelete(){
        $id = Yii::$app->request->post('id');
        Newcodedata::deleteAll(['id'=>$id]);
        return json_encode(['state'=>true,'msg'=>'删除成功']);
    }

    public function actionEdit(){
        $id = Yii::$app->request->get('id');
        $model = Newcodedata::find()->where(['id'=>$id])->one();

        return $this->render('_form',[
            'model' => $model
        ]);
    }

}