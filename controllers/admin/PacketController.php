<?php
/**
 * Created by PhpStorm.
 * User: yeyun
 * Date: 16-10-8
 * Time: 下午3:34
 */

namespace app\controllers\admin;

use app\models\Bjdata;
use app\models\Cqdata;
use app\models\Tjdata;
use app\models\Txdata;
use app\models\Xjdata;
use Yii;
use yii\web\UploadedFile;

class PacketController extends BaseController
{
    /**
     * @var array 允许上传的文件后缀
     */
    private $text = ['txt'];

    /**
     * 重庆数据包管理
     */
    public function actionCq(){
        $model = Cqdata::find()->all();
        return $this->render('index',['type'=>'cq','model'=>$model]);
    }

    /**
     * 天津数据包管理
     */
    public function actionTj(){
        $model = Tjdata::find()->all();
        return $this->render('index',['type'=>'tj','model'=>$model]);
    }

    /**
     * 新疆数据包管理
     */
    public function actionXj(){
        $model = Xjdata::find()->all();
        return $this->render('index',['type'=>'xj','model'=>$model]);
    }

    public function actionBj(){
        $model = Bjdata::find()->all();
        return $this->render('index',['type'=>'bj','model'=>$model]);
    }

    /**
     * 腾讯分分彩
     */
    public function actionTx(){
        $model = Txdata::find()->all();
        return $this->render('index',['type'=>'tx','model'=>$model]);
    }

    /**
     * 表单视图
     */
    public function actionForm(){
        $type = Yii::$app->request->get('type');
        if($type == 'cq'){
            $model = new Cqdata();
        }
        if($type == 'xj'){
            $model = new Xjdata();
        }
        if($type == 'tj'){
            $model = new Tjdata();
        }
        if($type == 'bj'){
            $model = new Bjdata();
        }
        if ($type == 'tx'){
            $model = new Txdata();
        }
        return $this->render('_form',[
            'model' => $model
        ]);
    }

    public function actionSubmit(){
        if(Yii::$app->request->post()){
            $type = Yii::$app->request->post('type');
            if($type == 'cq'){
                $model = new Cqdata();
                $post_name = 'Cqdata';
            }
            if($type == 'xj'){
                $model = new Xjdata();
                $post_name = 'Xjdata';
            }
            if($type == 'tj'){
                $model = new Tjdata();
                $post_name = 'Tjdata';
            }
            if($type == 'bj'){
                $model = new Bjdata();
                $post_name = 'Bjdata';
            }
            if ($type == 'tx'){
                $model = new Txdata();
                $post_name = 'Txdata';
            }

            $post = Yii::$app->request->post($post_name);
            if($post['id']){
                $model = $model->find()->where(['id'=>$post['id']])->one();
                if($model->validate() && $model->save()){
                    //编辑
                    $this->redirect('/admin/packet/'.$type);
                }
            }

            $model->load(Yii::$app->request->post());
            $model->time = time();
            if($model->validate() && $model->save()){
                //添加
                $this->redirect('/admin/packet/'.$type);
            }
        }
    }

    public function actionEdit(){
        $type = Yii::$app->request->get('type');
        $id = Yii::$app->request->get('id');
        if($type == 'cq'){
            $model = Cqdata::find()->where(['id'=>$id])->one();
        }
        if($type == 'xj'){
            $model = Xjdata::find()->where(['id'=>$id])->one();

        }
        if($type == 'tj'){
            $model = Tjdata::find()->where(['id'=>$id])->one();
        }
        if($type == 'bj'){
            $model = Bjdata::find()->where(['id'=>$id])->one();
        }
        return $this->render('_form',[
            'model' => $model
        ]);
    }

    /**
     * 数据包上传
     */
    public function actionUpload(){
        $this->checkFolder();
        $file = UploadedFile::getInstanceByName('file');
        if($file && $this->validFile($file)){
            $type = Yii::$app->request->post('type');
            $uploadFolder = $this->checkFolder();
            $fileName = 'data'.$type. '.' . $file->extension;
            if(file_exists($uploadFolder.$fileName)){
                unlink($uploadFolder.$fileName);
            }
            $file->saveAs($uploadFolder . $fileName);
            $contents = $this->readTxt($uploadFolder . $fileName);
            $contents ? $state = true : $state = false;
            return json_encode(['state'=>$state,'msg'=>!$contents ? '数据更新失败,文本数据格式不正确' : $contents]);
        }else{
            return json_encode(['state'=>false,'msg'=>'上传失败']);
        }
    }

    /**
     * 读取文件内容
     * @param $txtUrl
     * @return array|bool
     */
    private function readTxt($txtUrl){
        if(file_exists($txtUrl)){
            $content = file_get_contents($txtUrl);
            $content = str_replace("\r\n", ' ', $content); //把换行符 替换成空格
            $contentArr = explode(' ',$content);
            $contentArr = array_filter($contentArr);
            $txt = true;
            foreach($contentArr as $key=>$val){
                if(!intval($val)){
                    $txt = false;
                    break;
                }
            }
            if(!$txt){
                return false;
            }
            return file_get_contents($txtUrl);
        }
    }

    /*
     * 检查 用户上传是否是Image
     * */
    private function validFile($file){
        return in_array($file->extension,$this->text);
    }

    /*
     * 检查文件夹是否存在
     * */
    private function checkFolder(){
        $uploadFolder =  Yii::getAlias('@webroot').'/upload';
        if(!file_exists($uploadFolder)){
            mkdir($uploadFolder);
        }
        $uploadFolder = $uploadFolder . '/data';
        if(!file_exists($uploadFolder)){
            mkdir($uploadFolder);
        }
        return $uploadFolder . '/';
    }

    /**
     * 查看数据包
     */
    public function actionSee(){
        $type = Yii::$app->request->get('type');
        $id = Yii::$app->request->get('id');
        if($type == 'cq'){
            $model = Cqdata::find()->where(['id'=>$id])->one();
        }
        if($type == 'xj'){
            $model = Xjdata::find()->where(['id'=>$id])->one();
        }
        if($type == 'tj'){
            $model = Tjdata::find()->where(['id'=>$id])->one();
        }
        if($type == 'bj'){
            $model = Bjdata::find()->where(['id'=>$id])->one();
        }
        if($type == 'tx'){
            $model = Txdata::find()->where(['id'=>$id])->one();
        }
        return $this->render('see',['model'=>$model]);
    }

    /**
     * 删除数据包
     */
    public function actionDelete(){
        $type = Yii::$app->request->get('type');
        $id = Yii::$app->request->post('id');
        if($type == 'cq'){
            Cqdata::deleteAll(['id'=>$id]);
        }
        if($type == 'tj'){
            Tjdata::deleteAll(['id'=>$id]);
        }
        if($type == 'xj'){
            Xjdata::deleteAll(['id'=>$id]);
        }
        if($type == 'tx'){
            Txdata::deleteAll(['id'=>$id]);
        }
        return json_encode(['state'=>true,'msg'=>'删除成功']);
    }
}