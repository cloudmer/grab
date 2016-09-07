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

<div class="text-center more" style="margin-bottom: 30px" data-page="2" data-url="<?= \yii\helpers\Url::to('/home/xjssc')?>">
    <span style="padding: 9px 31px;" class="label label-success">加载更多</span>
</div>

<div class="text-center null" style="display: none;margin-bottom: 30px" data-page="2" data-url="<?= \yii\helpers\Url::to('/home/xjssc')?>">
    <span style="padding: 9px 31px;color: black" class="label">没有更多数据了..</span>
</div>

<?php return;?>


<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.js"></script>

<!--Includes-->
<link href="../components/mobiscroll.custom-2.5.0/mobiscroll.custom-2.5.0.min.css" rel="stylesheet" type="text/css" />
<script src="../components/mobiscroll.custom-2.5.0/mobiscroll.custom-2.5.0.min.js" type="text/javascript"></script>


<?php
$script = <<< JS
$(document).ready(function(){
    var opt = {
        preset: 'date', //日期
        theme: 'sense-ui', //皮肤样式
        display: 'modal', //显示方式 
        mode: 'scroller', //日期选择模式
        dateFormat: 'yy-mm-dd', // 日期格式
        setText: '确定', //确认按钮名称
        cancelText: '取消',//取消按钮名籍我
        dateOrder: 'yymmdd', //面板中日期排列格式
        dayText: '日', monthText: '月', yearText: '年', //面板中年月日文字
        endYear:2999, //结束年份
    };
    $('input:jqmData(role="datebox")').mobiscroll(opt).date(opt);
	//之前给群里共享发错了。记得之前写过一个，估计丢了。现在重写一个。并完善一下，下面注释部分是上面的参数可以替换改变它的样式
	//希望一起研究插件的朋友加我个人QQ也可以，本人也建个群 291464597 欢迎进群交流。哈哈。这个不能算广告。
	// 直接写参数方法
	//$("#scroller").mobiscroll(opt).date(); 
	// Shorthand for: $("#scroller").mobiscroll({ preset: 'date' });
	//具体参数定义如下
    //{
    //preset: 'date', //日期类型--datatime,
    //theme: 'ios', //皮肤其他参数【android-ics light】【android-ics】【ios】【jqm】【sense-ui】【sense-ui】【sense-ui】
								//【wp light】【wp】
    //mode: "scroller",//操作方式【scroller】【clickpick】【mixed】
    //display: 'bubble', //显示方【modal】【inline】【bubble】【top】【bottom】
    //dateFormat: 'yyyy-mm-dd', // 日期格式
    //setText: '确定', //确认按钮名称
    //cancelText: '清空',//取消按钮名籍我
    //dateOrder: 'yymmdd', //面板中日期排列格
    //dayText: '日', 
    //monthText: '月',
    //yearText: '年', //面板中年月日文字
    //startYear: (new Date()).getFullYear(), //开始年份
    //endYear: (new Date()).getFullYear() + 9, //结束年份
    //showNow: true,
    //nowText: "明天",  //
    //showOnFocus: false,
    //height: 45,
    //width: 90,
    //rows: 3}
})
JS;
$this->registerJs($script);
?>

<div class="form-group field-configure-start_time has-success">
    <label class="control-label" for="configure-start_time">开始时间</label>
    <input type="text" data-role="datebox"  id="txtBirthday" name="txtBirthday"  class="form-control" placeholder="查询时间">

    <p class="help-block help-block-error"></p>
</div>


<div data-role="fieldcontain">
    <label for="txtBirthday">Select Date:</label>
    <input type="text" data-role="datebox"   id="txtBirthday" name="txtBirthday" />
</div>