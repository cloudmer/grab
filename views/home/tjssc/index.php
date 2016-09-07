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
        var d = dialog({fixed: true}).show();
        var _this = $(this);
        _this.hide();
        var page = $(this).attr('data-page');
        var url = $(this).attr('data-url');
        $.get(url,{page:page},function(data){
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
</style>




<table class="table table-hover">
    <tbody class="contents">
        <tr>
            <th class="text-center">期号</th>
            <th class="text-center">号码</th>
            <th class="text-center">前三</th>
            <th class="text-center">中三</th>
            <th class="text-center">后三</th>
        </tr>

        <?= $this->render('_list',['model'=>$model])?>

    </tbody>
</table>

<div class="bottom_tools" style="bottom: 40px;">
    <a id="scrollUp" href="javascript:;" title="飞回顶部" style="display: block;"></a>
</div>

<div class="text-center more" style="margin-bottom: 30px" data-page="2" data-url="<?= \yii\helpers\Url::to('/home/tjssc')?>">
    <span style="padding: 9px 31px;" class="label label-success">加载更多</span>
</div>

<div class="text-center null" style="display: none;margin-bottom: 30px" data-page="2" data-url="<?= \yii\helpers\Url::to('/home/tjssc')?>">
    <span style="padding: 9px 31px;color: black" class="label">没有更多数据了..</span>
</div>
