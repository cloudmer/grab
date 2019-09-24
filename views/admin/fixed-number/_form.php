<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->registerJsFile('/js/fileUpload/ajaxfileupload.js');
$script = <<< JS
$(document).ready(function(){
   $("#upload-file").change(function(){
       var url  = $(this).attr('data-url');
       var type = $(this).attr('data-type');
       var csrf = $(this).attr('data-csrf');
       $.ajaxFileUpload({
            url: url,
            secureuri: true,
            fileElementId: 'upload-file',
            dataType: 'json',
            data:{_csrf:csrf},
            success: function (data, status) {
                if(data.state){
                    toastr.success('数据包格式检测成功');
                    $(".contents").val(data.msg);
                }else{
                    toastr.error(data.msg);
                }
            }
        });
   })
})
JS;
$this->registerJs($script);
?>

<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="#">

                            报警设置与数据包管理
                        </a>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="main-box">
            <header class="main-box-header clearfix">
                <h2>时时彩 报警时间段 [例: 09时 23时  24小时制度 24=0 最好不要设置 24 或者 0]</h2>
            </header>
            <div class="main-box-body clearfix">

                <?php $form = ActiveForm::begin([
                    'options'=>['class'=>'form-center data-form','enctype'=>'multipart/form-data'],
                    'action'=>Url::to('/admin/fixed-number/submit'),
                    'method'=>'post'])?>
                <div class="modal-body">
                    <?= $form->field($model, 'number')->label('统计号码')->textInput()?>
                    <?= $form->field($model, 'num')->label('报警期数')->textInput()?>
                    <?= $form->field($model, 'status')->label('是否开启报警')->dropDownList([1=>'开启',0=>'关闭'])?>
                    <?= $form->field($model, 'id')->label(false)->hiddenInput()?>

                    <input type="hidden" name="type" value="<?= isset($_GET['type']) ? $_GET['type'] : false ?>" />
                </div>

                <div class="btn-group pull-right" style="margin-right: 15px">
                    <?= Html::resetButton('重置',['class'=>'btn btn-success'])?>
                    <?= Html::submitButton('保存',['class'=>'btn btn-success right'])?>
                </div>
                <?php ActiveForm::end()?>


            </div>
        </div>
    </div>
</div>
