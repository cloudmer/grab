<?php

$csrf = Yii::$app->request->getCsrfToken();

$script = <<< JS
$(document).ready(function(){
    $('body').on('click','.mailer-active',function(){
        var d = dialog({fixed: true}).show();
        var _this = $(this);
        var json = {_csrf:"$csrf",id:_this.attr('data-id'),type:_this.attr('data-type')};
        $.post(_this.attr('data-url'),json,function(data){
            $("#myModal").html('');
            $("#myModal").append(data);
            d.close().remove();
            $('#myModal').modal('show');
        });
    });

    $('#myModal').on('hidden.bs.modal', function (e) {
        $(this).html('');
    })

    $("body").on('click','.mailer-delete',function(){
        var _this = $(this);
        var id = _this.attr('data-id');
        dialog({
            fixed: true,
            title: '删除',
            content: '您确定要删除吗?',
            ok: function () {
                var that = this;
                this.title('正在提交..');
                $.post(_this.attr('data-url'),{_csrf:"$csrf",id:id},function(data){
                    that.close().remove();
                    if(data.state == true){
                        $("#mailer-"+id).remove();
                        toastr.success('删除成功');
                    }
                },'json');
                return false;
            },
            cancel: function () {
                return true;
            }
        }).show();
    })

});
JS;
$this->registerJs($script);
?>

<div class="row">
    <div class="col-lg-12">
        <div class="main-box clearfix">
            <header class="main-box-header clearfix">
                <h2>收件邮箱</h2>
                <div class="btn-group" style="margin-top: 10px;margin-bottom: 10px">
                    <button data-url="<?= \yii\helpers\Url::to('/admin/mailer/from')?>" data-type="1" data-toggle="dropdown" class="mailer-active btn btn-primary dropdown-toggle has-tooltip" type="button" title="" data-original-title="Labels">
                        <i class="fa fa-plus-circle fa-lg"></i> 添加邮箱 &nbsp;
                    </button>
                </div>
            </header>
            <div class="main-box-body clearfix">
                <ul class="widget-todo addressee">
                    <?= $this->render('_list.php',['type'=>'recipients','recipients'=>$recipients])?>
                </ul>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="main-box clearfix">
            <header class="main-box-header clearfix">
                <h2>发件邮箱</h2>
                <div class="btn-group" style="margin-top: 10px;margin-bottom: 10px">
                    <button data-url="<?= \yii\helpers\Url::to('/admin/mailer/from')?>" data-type="0" data-toggle="dropdown" class="mailer-active btn btn-primary dropdown-toggle has-tooltip" type="button" title="" data-original-title="Labels">
                        <i class="fa fa-plus-circle fa-lg"></i> 添加邮箱 &nbsp;
                    </button>
                </div>
            </header>
            <div class="main-box-body clearfix">
                <ul class="widget-todo sender">
                    <?= $this->render('_list.php',['type'=>'sender','sender'=>$sender])?>
                </ul>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
