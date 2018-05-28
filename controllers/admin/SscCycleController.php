<?php
/**
 * Created by PhpStorm.
 * User: wang.haibo
 * Date: 2018/5/28
 * Time: 10:36
 */

namespace app\controllers\admin;


use app\models\SscCycle;
use yii\web\UploadedFile;
use \Yii;


/**
 * a包周期数
 *
 * Class SscCycleController
 * @package app\controllers\admin
 */
class SscCycleController extends BaseController
{

    /**
     * @var array 允许上传的文件后缀
     */
    private $text = ['txt'];

    public function actionIndex(){
        $model = SscCycle::find()->all();
        return $this->render('index',['model'=>$model]);
    }

    /**
     * 表单视图
     */
    public function actionForm(){
        return $this->render('_form',[
            'model' => new SscCycle()
        ]);
    }

    public function actionSubmit()
    {
        if (Yii::$app->request->post()) {
            $model = new SscCycle();
            $post = Yii::$app->request->post('SscCycle');
            if ($post['id']) {
                $model = $model->find()->where(['id' => $post['id']])->one();
                if ($model->validate() && $model->save()) {
                    //编辑
                    $this->redirect('/admin/ssc-cycle/');
                }
            }

            $model->load(Yii::$app->request->post());
            $model->created_at = date('Y-m-d H:i:s');
            if ($model->validate() && $model->save()) {
                //添加
                $this->redirect('/admin/ssc-cycle/');
            }
        }
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
        $id = Yii::$app->request->get('id');
        $model = SscCycle::find()->where(['id'=>$id])->one();
        return $this->render('see',['model'=>$model]);
    }

    public function actionEdit(){
        $id = Yii::$app->request->get('id');
        $model = SscCycle::find()->where(['id'=>$id])->one();
        return $this->render('_form',[
            'model' => $model
        ]);
    }

    /**
     * 删除数据包
     */
    public function actionDelete(){
        $id = Yii::$app->request->post('id');
        SscCycle::deleteAll(['id'=>$id]);
        return json_encode(['state'=>true,'msg'=>'删除成功']);
    }
}