<?php
/**
 * Created by PhpStorm.
 * User: 夜云
 * Date: 2015/10/10
 * Time: 15:26
 */

use yii\helpers\Url;
$this->registerJsFile('/js/fileUpload/ajaxfileupload.js');
$this->registerJsFile('/components/cropper/dist/cropper.js');
$this->registerJsFile('/components/cropper/init.js');
$this->registerCssFile('/components/cropper/dist/cropper.css');
$this->registerCssFile('/css/cropper.css');
$this->registerCssFile('/components/cropper/css/fileinput.min.css');


$csrf = Yii::$app->request->getCsrfToken();
?>

<div class="btn btn-primary btn-file" title="选择图像">
    <i class="glyphicon glyphicon-folder-open"></i>
    <span class="hidden-xs">选择图像</span>
    <input type="file" class="inputImage" data-url="<?= Url::to('/image/upload') ?>" name="file" accept="image/*">
</div>


<!-- Modal -->
<div class="modal fade" id="cropper-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Image 裁剪</h4>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <img src="" alt="Picture">
                </div>

                <div class="docs-actions text-center">
                    <div class="btn-group">
                        <span type="button" class="btn btn-primary" data-method="zoom" data-option="0.1">
                            <span class="docs-tooltip" data-toggle="tooltip" title="放大">
                                <span class="fa fa-search-plus"></span>
                            </span>
                        </span>
                        <span type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1">
                            <span class="docs-tooltip" data-toggle="tooltip" title="缩小">
                                <span class="fa fa-search-minus"></span>
                            </span>
                        </span>
                    </div>

                    <div class="btn-group">
                        <span type="button" class="btn btn-primary" data-method="rotate" data-option="-45">
                            <span class="docs-tooltip" data-toggle="tooltip" title="左旋转">
                                <span class="fa fa-rotate-left"></span>
                            </span>
                        </span>
                        <span type="button" class="btn btn-primary" data-method="rotate" data-option="45">
                            <span class="docs-tooltip" data-toggle="tooltip" title="右旋转">
                                <span class="fa fa-rotate-right"></span>
                            </span>
                        </span>
                    </div>

                    <div class="btn-group">
                        <span type="button" class="btn btn-primary" data-flip="horizontal" data-method="scale" data-option="-1" data-second-option="1">
                            <span class="docs-tooltip" data-toggle="tooltip" title="左右颠倒">
                              <span class="fa fa-arrows-h"></span>
                            </span>
                        </span>
                        <span type="button" class="btn btn-primary" data-flip="vertical" data-method="scale" data-option="1" data-second-option="-1">
                            <span class="docs-tooltip" data-toggle="tooltip" title="上下颠倒">
                                <span class="fa fa-arrows-v"></span>
                            </span>
                        </span>
                    </div>

                    <div class="btn-group">
                        <span type="button" class="btn btn-primary" data-method="reset">
                            <span class="docs-tooltip" data-toggle="tooltip" title="初始化">
                                <span class="fa fa-refresh"></span>
                            </span>
                        </span>

                        <span class="btn btn-primary btn-file" title="重新选择">
                            <i class="glyphicon glyphicon-folder-open"></i>
                            <input type="file" class="inputImage" data-url="<?= Url::to('/image/upload') ?>" name="file" accept="image/*">
                        </span>
                    </div>

                    <button type="button" id="image-upload" data-csrf="<?= $csrf ?>" data-loading-text="Loading..." class="btn btn-primary" ><i class="glyphicon glyphicon-send"></i>&nbsp;上传</button>
                </div>
            </div>

        </div>
    </div>
</div>

