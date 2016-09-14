<meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no">
<?php
$this->registerJsFile('/js/fileUpload/ajaxfileupload.js');
$csrf = Yii::$app->request->getCsrfToken();
$script = <<< JS
$(document).ready(function(){
    
	$('#scrollUp').click(function (e) {
		e.preventDefault();
		$('html,body').animate({ scrollTop:0});
	});
    
    $(".label-info").click(function(){
        var txt = $(this).text();
        var txtArr = txt.split(' - ');
        if(txtArr[0] == '点击查看'){
            var str = '点击关闭';
        }else{
            var str = '点击查看';
        }
        var newStr = str+' - '+txtArr[1];
        $(this).text(newStr);
    })
    
    //加载更多
    $(".more").click(function(){
        var type = $type;
        var d = dialog({fixed: true}).show();
        var _this = $(this);
        _this.hide();
        var page = $(this).attr('data-page');
        var url = $(this).attr('data-url');
        $.get(url,{page:page,type:type},function(data){
            d.close().remove();
            if(data == false){
                $(".null").css({display:'block'});
                return;
            }
            $(".contents").append(data);
            _this.attr('data-page',parseInt(page)+1);
            _this.show();
        });
    })
    
    $(".form-control").change(function() {
        $("form").submit();
    })
    
    
})
JS;
$this->registerJs($script);
?>

<style>
    .bottom_tools {
        position: fixed;
        z-index: 1070;
        right: 30px;
        bottom: 40px;
    }

    #scrollUp{
        border-radius: 100px;
        width: 45px;
        height: 45px;
        background-image: url(/images/backgrounds.32.png);
        background-position: -100px -53px;
        display: none;
    }
    .row{
        margin-right:0;
        margin-left:0;
    }

    .row select{
        margin: 5px 0 5px 0;
    }
</style>


<div style="margin: 0 10px 0 10px">
    <div class="row">
        <form action="" method="get">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <select class="form-control" name="type">
                    <option value="1" <?php if($type == 1){ echo 'selected="selected"'; }?> >数据包1</option>
                    <option value="2" <?php if($type == 2){ echo 'selected="selected"'; }?> >数据包2</option>
                </select>
            </div>
        </form>
    </div>
</div>

<table class="table table-hover">
    <tbody class="contents">
    <tr>
        <th class="text-center">期号</th>
        <th class="text-center">号码</th>
        <th class="text-center">前三</th>
        <th class="text-center">中三</th>
        <th class="text-center">后三</th>
    </tr>

    <?= $this->render('_list',['model'=>$model,'type'=>$type])?>

    </tbody>
</table>

<div class="bottom_tools" style="bottom: 40px;">
    <a id="scrollUp" href="javascript:;" title="飞回顶部" style="display: block;"></a>
</div>

<div class="text-center more" style="margin-bottom: 30px" data-page="2" data-url="<?= \yii\helpers\Url::to('/home/xjssc')?>">
    <span style="padding: 9px 31px;" class="label label-success">加载更多</span>
</div>

<div class="text-center null" style="display: none;margin-bottom: 30px" data-page="2" data-url="<?= \yii\helpers\Url::to('/home/xjssc')?>">
    <span style="padding: 9px 31px;color: black" class="label">没有更多数据了..</span>
</div>