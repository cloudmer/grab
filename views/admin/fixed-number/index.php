<?php
use yii\helpers\Url;

$csrf = Yii::$app->request->getCsrfToken();
$script = <<< JS
$(document).ready(function(){
    $('body').on('click','.menu-active',function(){
        var _this = $(this);
        window.location.href=_this.attr('data-url'); 
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
    
});
JS;
$this->registerJs($script);
?>
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li class="active"><span>11选5固定号码统计报警设置</span></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="main-box clearfix">
            <header class="main-box-header clearfix">
                <h2>11选5固定号码统计报警设置</h2>

                <div class="btn-group pull-right">
                    <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle has-tooltip" type="button" title="" data-original-title="Labels">
                        <i class="fa fa-plus-circle fa-lg"></i> 添加报警 &nbsp; <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="menu-active" data-url="<?= Url::to('/admin/fixed-number/form?type=0') ?>" data-type="0" href="#"><i class="fa fa-circle green"></i> 所有彩种</a></li>
                        <li><a class="menu-active" data-url="<?= Url::to('/admin/fixed-number/form?type=1') ?>" data-type="1" href="#"><i class="fa fa-circle green"></i> 江西</a></li>
                        <li><a class="menu-active" data-url="<?= Url::to('/admin/fixed-number/form?type=2') ?>" data-type="2" href="#"><i class="fa fa-circle purple"></i> 广东</a></li>
                        <li><a class="menu-active" data-url="<?= Url::to('/admin/fixed-number/form?type=3') ?>" data-type="3" href="#"><i class="fa fa-circle yellow"></i> 山东</a></li>
                        <li><a class="menu-active" data-url="<?= Url::to('/admin/fixed-number/form?type=4') ?>" data-type="4" href="#"><i class="fa fa-circle yellow"></i> 上海</a></li>
                    </ul>
                </div>

            </header>
            <div class="main-box-body clearfix">
                <div class="table-responsive">
                    <table id="table-example-fixed" class="table table-hover">
                        <thead>
                        <tr>
                            <th>数据类型</th>
                            <th>统计号码</th>
                            <th>报警期数</th>
                            <th>报警状态</th>
                            <th>管理操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?= $this->render('/admin/fixed-number/_list',['model'=>$model]) ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



