<?php
/**
 * Created by PhpStorm.
 * User: yeyun
 * Date: 17-4-21
 * Time: 上午11:05
 */

namespace app\controllers\admin;


use app\models\Packet;
use Yii;

class DataPacketController extends BaseController
{

    public function actionIndex(){
        $model = Packet::find()->all();
        return $this->render('index', ['model' => $model]);
    }

    /**
     * 表单视图
     */
    public function actionForm(){
        $type = Yii::$app->request->get('type');
        $model = new Packet();
        return $this->render('_form',[
            'type' => $type,
            'model' => $model
        ]);
    }

    public function actionSubmit(){
        if(Yii::$app->request->post()){
            $type = Yii::$app->request->post('type');
            $model = new Packet();

            $post = Yii::$app->request->post('Packet');
            if($post['id']){
                $model = $model->find()->where(['id'=>$post['id']])->one();
                if($model->validate() && $model->save()){
                    //编辑
                    $this->redirect('/admin/data-packet/index');
                }
            }

            //添加全部
            if($post['type'] == 0){
                $model->load(Yii::$app->request->post());
                for ($i=1; $i<=4; $i++){
                    $m = new Packet();
                    $m->type          = $i;
                    $m->alias         = Yii::$app->request->post('Packet')['alias'];
                    $m->data_txt      = Yii::$app->request->post('Packet')['data_txt'];
                    $m->start         = Yii::$app->request->post('Packet')['start'];
                    $m->end           = Yii::$app->request->post('Packet')['end'];
                    $m->regret_number = Yii::$app->request->post('Packet')['regret_number'];
                    $m->forever       = Yii::$app->request->post('Packet')['forever'];
                    $m->state         = Yii::$app->request->post('Packet')['state'];
                    $m->time          = time();
                    $m->cycle         = Yii::$app->request->post('Packet')['cycle'];
                    $m->cycle_number  = Yii::$app->request->post('Packet')['cycle_number'];
                    $m->save();
                }
                $this->redirect('/admin/data-packet/index');
                return;
            }

            //单个添加
            $model->load(Yii::$app->request->post());
            $model->time = time();
            if($model->validate() && $model->save()){
                //添加
                $this->redirect('/admin/data-packet/index');
            }
        }
    }

    /**
     * 查看数据包
     */
    public function actionSee(){
        $id = Yii::$app->request->get('id');
        $model = Packet::find()->where(['id' => $id])->one();
        return $this->render('see',['model'=>$model]);
    }

    /**
     * 删除数据包
     */
    public function actionDelete(){
        $id = Yii::$app->request->post('id');
        Packet::deleteAll(['id'=>$id]);
        return json_encode(['state'=>true,'msg'=>'删除成功']);
    }

    public function actionEdit(){
        $type = Yii::$app->request->get('type');
        $id = Yii::$app->request->get('id');
        $model = Packet::find()->where(['id'=>$id])->one();

        return $this->render('_form',[
            'type' => $type,
            'model' => $model
        ]);
    }

}