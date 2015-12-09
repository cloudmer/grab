<?php

namespace app\controllers;

use Yii;
use yii\helpers\Json;
use yii\web\UploadedFile;
use yii\web\Controller;

class ImageController extends Controller
{
    public $image = ['png','jpeg','gif','jpg'];

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionUpload(){
        $this->checkFolder();
        if (Yii::$app->request->isPost) {
            $file = UploadedFile::getInstanceByName('file');
            if ($file&&$this->validFile($file)) {
                $uploadFolder = $this->checkFolder();
                $fileName = time() . rand(100, 999) . '.' . $file->extension;
                $file->saveAs($uploadFolder . $fileName);
                $path = str_replace(Yii::getAlias('@webroot'),'',$uploadFolder . $fileName);
                $data = Yii::$app->request->getQueryParam('crop-data');
                if($data!=null){
                    $this->cropImage($path,$data,$file->extension);
                }
                $result = ['code'=>true,'message'=>'上传成功','data'=>$path];
            }else{
                $result = ['code'=>false,'message'=>'上传失败'];
            }
        }
        echo Json::encode($result);
    }

    /*
     * 检查 用户上传是否是Image
     * */
    private function validFile($file){
        return in_array($file->extension,$this->image);
    }

    /*
     * 裁剪Image
     * */
    private function cropImage($path,$post,$extension,$maxSize = ['width'=>450,'height'=>300])
    {
        $data = Json::decode($post);
        if (!empty($path) && !empty($data)) {
            $rootFolder = Yii::getAlias('@webroot');
            if($extension=='jpg'){
                $type =  IMAGETYPE_JPEG;
            }else{
                $type = \exif_imagetype($rootFolder.$path);
            }
            $filenameAbs = $rootFolder . $path;
            switch ($type) {
                case IMAGETYPE_GIF:
                    $src_img = imagecreatefromgif($filenameAbs);
                    break;

                case IMAGETYPE_JPEG:
                    $src_img = imagecreatefromjpeg($rootFolder.$path);
                    break;

                case IMAGETYPE_PNG:
                    $src_img = imagecreatefrompng($rootFolder.$path);
                    break;
            }

            if (!$src_img) {
                return;
            }

            $size = getimagesize($filenameAbs);
            $src_img_w = $size[0]; // natural width
            $src_img_h = $size[1]; // natural height


            $tmp_img_w = $data['width'];
            $tmp_img_h = $data['height'];

            $src_x = $data['x'];
            $src_y = $data['y'];

            if($src_x<1){
                $src_x = 0;
            }
            if($src_y<1){
                $src_y = 0;
            }

            $dst_x = 0;
            $dst_y = 0;
            $dst_w = $tmp_img_w;
            $dst_h = $tmp_img_h;
//            if($tmp_img_h>$maxSize['height']){
//                $dst_h = $maxSize['height'];
//            }
//            if($tmp_img_w>$maxSize['width']){
//                $dst_w = $maxSize['width'];
//            }

            $dst_img = imagecreatetruecolor($dst_w, $dst_h);

            imagefill($dst_img, 0, 0, imagecolorallocatealpha($dst_img, 0, 0, 0, 127));
            imagesavealpha($dst_img, true);

            imagecopyresampled($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $tmp_img_w, $tmp_img_h);
            imagepng($dst_img, $filenameAbs);
            imagedestroy($src_img);
            imagedestroy($dst_img);
        }
    }

    /*
     * 检查文件夹是否存在
     * */
    private function checkFolder(){
        $uploadFolder =  Yii::getAlias('@webroot').'/upload';
        if(!file_exists($uploadFolder)){
            mkdir($uploadFolder);
        }
        $uploadFolder = $uploadFolder . '/' . date('Ymd',time());
        if(!file_exists($uploadFolder)){
            mkdir($uploadFolder);
        }
        return $uploadFolder . '/';
    }

}
