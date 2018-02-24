<?php
use yii\helpers\Url;

$csrf = Yii::$app->request->getCsrfToken();
$script = <<< JS
$(document).ready(function(){
    $('body').on('click','.add-reserve',function(){
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
                    <li class="active"><span>间隔几连号 - 报警设置</span></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="main-box clearfix">
            <header class="main-box-header clearfix">
                <h2>间隔几连号 - 报警设置</h2>

                <div class="btn-group pull-right">
                    <button data-toggle="dropdown" data-url="/admin/play2/form" class="btn btn-primary dropdown-toggle has-tooltip add-reserve" type="button" title="" data-original-title="Labels">
                        <i class="fa fa-plus-circle fa-lg"></i> 添加 &nbsp;
                    </button>
                </div>

            </header>
            <div class="main-box-body clearfix">
                <div class="table-responsive">
                    <table id="table-example-fixed" class="table table-hover">
                        <thead>
                        <tr>
                            <th>彩种类型</th>
                            <th>报警期数</th>
                            <th>报警开始时间</th>
                            <th>报警结束时间</th>
                            <th>报警状态</th>
                            <th>类型</th>
                            <th>报警周期</th>
                            <th>管理操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?= $this->render('/admin/play2/_list',['model'=>$model]) ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



