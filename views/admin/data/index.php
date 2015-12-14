<?php
use yii\helpers\Url;


$this->registerJsFile('/js/fileUpload/ajaxfileupload.js');
$script = <<< JS
$(document).ready(function(){
    $(".upload-file").click(function(){
        $("#upload-file").trigger("click");
    })

    $("#upload").click(function(){
        if(!$('#upload-file').val()){
            toastr.error('您还没有上传呢');
            return;
        }
        var url = $('#upload-file').attr('data-url');
        var csrf = $("#upload-file").attr('data-csrf');
        $.ajaxFileUpload({
            url: url,
            secureuri: true,
            fileElementId: 'upload-file',
            dataType: 'json',
            data:{_csrf:csrf,type:1},
            success: function (data, status) {
                if(data.state){
                    toastr.success('上传成功,页面即将刷新');
                    window.location.reload();
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
        <div class="main-box clearfix">
            <header class="main-box-header clearfix">
                <h2>数据管理</h2>
                <div>
                    提示说明:上传文件只能是 txt 文本格式<br/>
                    上传数据说明:数据格式如下<br/>
                    01 02 03 04 05<br/>
                    01 02 03 04 04<br/>
                    ...<br/>
                    每5个号为一组,一组后面跟个换行,每个号码后面,打一个拼音输入法下的小写的空格(而非输入法汉字下的空格)<br/>
                    友情提示:开启开奖号码单数都有个0 比如:01 02 03 04 05,那么上传的文本也最好按这种格式上传<br/>
                    根据用户需求,每期开奖号码都按从小到大排列,上传数据本也最好按从小到大写好,(虽然程序里都按从小到大排列了)
                </div>
                <div class="btn-group pull-right">
                    <button data-toggle="dropdown" class="upload-file btn btn-primary dropdown-toggle has-tooltip" type="button" title="" data-original-title="Labels">
                        <i class="glyphicon glyphicon-folder-open"></i> 导入文本数据 &nbsp;
                    </button>
                    <input class="hidden" type="file" id="upload-file" name="file" data-csrf="<?= Yii::$app->request->getCsrfToken() ?>" data-url="<?= Url::to('/admin/data/upload')?>" >
                    <button style="margin-left: 10px" data-toggle="dropdown" id="upload" class="btn btn-primary dropdown-toggle has-tooltip" type="button" title="" data-original-title="Labels">
                        <i class="fa fa-cloud-upload"></i> 上传替换数据 &nbsp;
                    </button>
                </div>

            </header>
            <div class="main-box-body clearfix">
                <div class="table-responsive">
                    <table id="table-example-fixed" class="table table-hover">
                        <thead>
                        <tr>
                            <th>号组</th>
                            <th>位(1)</th>
                            <th>位(2)</th>
                            <th>位(3)</th>
                            <th>位(4)</th>
                            <th>位(5)</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?= $this->render('_list',['data'=>$data]) ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>