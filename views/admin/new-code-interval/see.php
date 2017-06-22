<?php
use yii\helpers\Url;


$this->registerJsFile('/js/fileUpload/ajaxfileupload.js');
$script = <<< JS
$(document).ready(function(){
    $(".upload-file").click(function(){
        $("#upload-file").trigger("click");
    })

    $("#upload").click(function(){
        var alias = $("#alias").val();
        if(!alias){
            toastr.error('请先填写 数据包别名');
            return false;
        }
        
        if(!$('#upload-file').val()){
            toastr.error('您还没有上传呢');
            return;
        }
        var url  = $('#upload-file').attr('data-url');
        var type = $('#upload-file').attr('data-type');
        var csrf = $("#upload-file").attr('data-csrf');
        $.ajaxFileUpload({
            url: url,
            secureuri: true,
            fileElementId: 'upload-file',
            dataType: 'json',
            data:{_csrf:csrf,type:type,alias:alias},
            success: function (data, status) {
                if(data.state){
                    toastr.success('上传成功,页面即将跳转');
                    window.location.href='/admin/packet/'+type; 
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
                    <li><a href="#">数据管理</a></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-lg-12">
        <div class="form-group field-menus-name required has-">
            <label class="control-label" for="menus-name">数据包别名</label>
            <input type="text" id="alias"value="<?= $model->alias ?>" class="form-control" name="alias" placeholder="数据包别名">
        </div>
    </div>

    <div class="col-lg-12">
        <div class="main-box clearfix">
            <div class="main-box-body clearfix">
                <div class="table-responsive">
                    <table id="table-example-fixed" class="table table-hover">
                        <thead>
                        <tr>
                            <th>号组</th>
                            <th>号(1)</th>
                            <th>号(2)</th>
                            <th>号(3)</th>
                            <th>号(4)</th>
                            <th>号(5)</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        if(!$model->contents){
                            return false;
                        }
                        $index = 0;
                        $content = str_replace("\r\n", ' ', $model->contents); //把换行符 替换成空格
                        $contentArr = explode(' ',$content);
                        $contentArr = array_filter($contentArr);
                        $contentArr = array_chunk($contentArr,5);
                        ?>
                        <?php foreach($contentArr as $val): ?>
                            <tr>
                                <td><span class="label label-success">组-<?= $index = $index+1?></span></td>
                                <?php foreach($val as $v): ?>
                                    <td><?= $v ?></td>
                                <?php endforeach ?>
                            </tr>
                        <?php endforeach ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>