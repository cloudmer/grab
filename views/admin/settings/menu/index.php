<?php
use yii\helpers\Url;

$csrf = Yii::$app->request->getCsrfToken();
$script = <<< JS
$(document).ready(function(){
    $('body').on('click','.menu-active',function(){
        var d = dialog({fixed: true}).show();
        var _this = $(this);
        var id = _this.attr('data-id');
        if(id){
            var json = {_csrf:"$csrf",id:id};
        }else{
            var json = {_csrf:"$csrf",type:_this.attr('data-type')};
        }
        $.post(_this.attr('data-url'),json,function(data){
            $("#myModal").html('');
            $("#myModal").append(data);
            d.close().remove();
            $('#myModal').modal('show');
        });
    });

    $('body').on('click','.menu-delete',function(){
        var _this = $(this);
        var content = _this.attr('data-info');
        var id = _this.attr('data-id');
        dialog({
            fixed: true,
            title: '您确定要删除吗?',
            content: content,
            ok: function () {
                var that = this;
                this.title('正在提交..');
                $.post(_this.attr('data-url'),{_csrf:"$csrf",id:id},function(data){
                    that.close().remove();
                    if(data.state == true){
                        $("#menu-"+id).remove();
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

    $('body').on('change','.is-show',function(){
        var _this = $(this);
        var state = _this.attr('data-state');
        $.post(_this.attr('data-url'),{_csrf:"$csrf",id:_this.attr('id'),state:state},function(data){
            if(data.state==true){
                _this.attr('data-state',data.state);
                toastr.success('操作成功,页面即将刷新');
                setTimeout(function () {
                    window.location.reload();
                }, 2000);
            }
        },'json');
    })

    $('#myModal').on('hidden.bs.modal', function (e) {
        $(this).html('');
    })

});
JS;
$this->registerJs($script);
?>
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="<?= Url::to('/admin/manage')?>">设置</a></li>
                    <li class="active"><span>菜单栏</span></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="main-box clearfix">
            <header class="main-box-header clearfix">
                <h2>菜单栏管理</h2>

                <div class="btn-group pull-right">
                    <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle has-tooltip" type="button" title="" data-original-title="Labels">
                        <i class="fa fa-plus-circle fa-lg"></i> 添加菜单 &nbsp; <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="menu-active" data-url="<?= Url::to('/admin/settings/form') ?>" data-type="1" href="#"><i class="fa fa-circle green"></i> + 顶级菜单</a></li>
                        <li><a class="menu-active" data-url="<?= Url::to('/admin/settings/form') ?>" data-type="2" href="#"><i class="fa fa-circle purple"></i> + 二级级菜单</a></li>
                        <li><a class="menu-active" data-url="<?= Url::to('/admin/settings/form') ?>" data-type="3" href="#"><i class="fa fa-circle yellow"></i> + 三级菜单</a></li>
                    </ul>
                </div>

            </header>
            <div class="main-box-body clearfix">
                <div class="table-responsive">
                    <table id="table-example-fixed" class="table table-hover">
                        <thead>
                        <tr>
                            <th>类型</th>
                            <th>菜单名</th>
                            <th>Icon</th>
                            <th>排序</th>
                            <th>开启/关闭</th>
                            <th>管理操作</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?= $this->render('menus',['model'=>$model]) ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>


