<meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no">
<?php
$csrf = Yii::$app->request->getCsrfToken();

$script = <<< JS
$(document).ready(function(){
    $('form').submit(function() {
        if(!$("#cp_type").val()){
            return false;
        }
        if(!$("#type").val()){
            return false;
        }
        if(!$("#cp_unit").val()){
            return false;
        }
        if(!$("#cp_unit_val").val()){
            return false;
        }
    })
    
    $("#search").click(function(){
        $(".search_div").show();
    })
    
    $("._close").click(function() {
        $(".search_div").hide();
    })
    
    $(".c_type li").click(function(){
        $(".c_type li").removeClass('on');
        $(this).addClass('on');
        var val = $(this).attr('data-val');
        var url = $('.c_type').attr('data-url');
        $.post(url,{type:val},function(data) {
            // console.log(data);
            if(data){
                var html = '';
                for(var i in data){
                    html += '<li data-id="'+data[i]['id']+'">'+data[i]['alias']+'</li>';
                }
                if(html){
                    $('.data_packet').html(html);
                }
                $("#cp_type").val(val);
            }
        },'json')
    })
    
    $(document).on('click','.data_packet li',function() {
        $('.data_packet li').removeClass('on');
        $(this).addClass('on');
        var data_id = $(this).attr('data-id');
        $("#type").val(data_id);
    })
    
    $(".unit li").click(function(){
        var val = $(this).attr('data-val');
        $('.unit li').removeClass('on');
        $(this).addClass('on');
        $("#cp_unit").val(val);
    })
    
    $(".unit_value li").click(function(){
        var val = $(this).attr('data-val');
        $('.unit_value li').removeClass('on');
        $(this).addClass('on');
        $("#cp_unit_val").val(val);
    })
    
	$('#scrollUp').click(function (e) {
		e.preventDefault();
		$('html,body').animate({ scrollTop:0});
	});
    
    var currYear = (new Date()).getFullYear();	
    var opt={};
    opt.date = {preset : 'date'};
	//opt.datetime = { preset : 'datetime', minDate: new Date(2012,3,10,9,22), maxDate: new Date(2014,7,30,15,44), stepMinute: 5  };
	opt.datetime = {preset : 'datetime'};
	opt.time = {preset : 'time'};
	opt.default = {
	    theme: 'android-ics light', //皮肤样式
        display: 'modal', //显示方式 
		mode: 'scroller', //日期选择模式
	    lang:'zh',
		startYear:currYear - 10, //开始年份
		endYear:currYear + 50 //结束年份
	};

	$("#appDate").val('').scroller('destroy').scroller($.extend(opt['date'], opt['default']));
    var optDateTime = $.extend(opt['datetime'], opt['default']);
    var optTime = $.extend(opt['time'], opt['default']);
    $("#appDateTime").mobiscroll(optDateTime).datetime(optDateTime);
    $("#appTime").mobiscroll(optTime).time(optTime);
			
	//下面注释部分是上面的参数可以替换改变它的样式
	//希望一起研究插件的朋友加我个人QQ也可以，本人也建个群 291464597 欢迎进群交流。哈哈。这个不能算广告。
	// 直接写参数方法
	//$("#scroller").mobiscroll(opt).date(); 
	// Shorthand for: $("#scroller").mobiscroll({ preset: 'date' });
	//具体参数定义如下
	//{
	    //preset: 'date', //日期类型--datatime --time,
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

<script src="../components/mobiscroll.custom-2.5.0/dev/jquery-1.9.1.js"></script>

<script src="../components/mobiscroll.custom-2.5.0/dev/js/mobiscroll.core-2.5.2.js" type="text/javascript"></script>
<script src="../components/mobiscroll.custom-2.5.0/dev/js/mobiscroll.core-2.5.2-zh.js" type="text/javascript"></script>

<link href="../components/mobiscroll.custom-2.5.0/dev/css/mobiscroll.core-2.5.2.css" rel="stylesheet" type="text/css" />
<link href="../components/mobiscroll.custom-2.5.0/dev/css/mobiscroll.animation-2.5.2.css" rel="stylesheet" type="text/css" />
<script src="../components/mobiscroll.custom-2.5.0/dev/js/mobiscroll.datetime-2.5.1.js" type="text/javascript"></script>
<script src="../components/mobiscroll.custom-2.5.0/dev/js/mobiscroll.datetime-2.5.1-zh.js" type="text/javascript"></script>

<!-- S 可根据自己喜好引入样式风格文件 -->
<script src="../components/mobiscroll.custom-2.5.0/dev/js/mobiscroll.android-ics-2.5.2.js" type="text/javascript"></script>
<link href="../components/mobiscroll.custom-2.5.0/dev/css/mobiscroll.android-ics-2.5.2.css" rel="stylesheet" type="text/css" />
<!-- E 可根据自己喜好引入样式风格文件 -->

<style>
    .action-btn{
        margin-top: 2rem;
        width: 80%;
        margin-left: 10%;
    }
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

    #search{
        margin-bottom: 10px;
        border-radius: 100px;
        width: 45px;
        height: 45px;
        background-image: url(/images/backgrounds.32.png);
        background-position: -238px -46px;
        display: none;
    }

    .row{
        margin-right:0;
        margin-left:0;
    }
    .row input{
        margin: 5px 0 5px 0;
    }
    .row select{
        margin: 5px 0 5px 0;
    }
</style>

<style type="text/css">
    /**{*/
        /*margin: 0;*/
        /*padding: 0;*/
    /*}*/

    ul,li{
        list-style: none;
    }
    .search_div{
        display: none;
        position:absolute;
        left:0;
        top:0;
        z-index:1000000;
        height:100%;
        width:100%;
        /*position: relative;*/
    }
    .select_box{
        width: 100%;
        height: 100%;
        min-height: 3rem;
        background: #333333;
        opacity: .9;
        position: fixed;
        left: 0;
        top: 4rem;
    }
    .select_box ul{
        width: 90%;
        margin: .5rem 5%;
        border-top: 1px solid #ffffff;
        clear: both;
    }
    .select_box ul li{
        display: block;
        height: 2rem;
        color: #ffffff;
        float: left;
        min-width: 3rem;
        line-height: 2rem;
        text-align: center;
        font-size: 2rem;
        margin: 0.6rem 0;
    }
    .ul_type{
        border: none !important;
    }
    .ul_type li,.ul_group li,.ul_nuit li{
        width: 33%;
    }
    .select_box ul li.on{
        color: yellow;
    }
    .ul_data{
        height: 8rem;
        overflow: auto;
    }
    .ul_data li{
        width: 50%;
        white-space:nowrap;
        text-overflow:ellipsis;
        -o-text-overflow:ellipsis;
        overflow: hidden;
    }
</style>



<?php if($error_msg): ?>
    <div class="alert alert-warning" role="alert" style="margin: 24px"><?php echo '警告：'.$error_msg.'!!! ' ?></div>
    <?php return;?>
<?php endif ?>


<?php if($model):  ?>
    <table class="table table-hover" style="margin-top: 10px">
        <tbody class="contents">
        <tr>
            <th class="text-center">期号</th>
            <th class="text-center">号码</th>
            <th class="text-center">前三</th>
            <th class="text-center">中三</th>
            <th class="text-center">后三</th>
        </tr>

        <?= $this->render('_list',['model'=>$model,'type'=>$type,'name'=>$name,'unit'=>$unit,'unit_val'=>$unit_val,'data_txt_id'=>$data_txt_id])?>

        </tbody>
    </table>
<?php else: ?>

    <div class="text-center null" style="display: block; margin-bottom: 30px; margin-top: 40px;" data-page="2" data-url="/home/xjssc">
        <span style="padding: 9px 31px;color: black" class="label">没有更多数据了..</span>
    </div>

<?php endif; ?>



<div class="bottom_tools" style="bottom: 40px;">
    <a id="search" href="javascript:;" title="飞回顶部" style="display: block;"></a>
    <a id="scrollUp" href="javascript:;" title="飞回顶部" style="display: block;"></a>
</div>



<div class="search_div select_box">
    <section class="">
        <ul class="ul_type c_type" data-url="<?= \yii\helpers\Url::to('/home/data-packet')?>" >
            <li data-val="cq">重庆</li>
            <li data-val="tj">天津</li>
            <li data-val="xj">新疆</li>
        </ul>
        <ul class="ul_data data_packet"></ul>
        <ul class="ul_group unit">
            <li data-val="1">万位</li>
            <li data-val="2">千位</li>
            <li data-val="3">百位</li>
            <li data-val="4">十位</li>
            <li data-val="5">个位</li>
        </ul>
        <ul class="ul_nuit unit_value">
            <li data-val="0">0</li>
            <li data-val="1">1</li>
            <li data-val="2">2</li>
            <li data-val="3">3</li>
            <li data-val="4">4</li>
            <li data-val="5">5</li>
            <li data-val="6">6</li>
            <li data-val="7">7</li>
            <li data-val="8">8</li>
            <li data-val="09">9</li>
        </ul>
    </section>

    <form action="" method="post">
        <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">

        <input type="hidden" id="cp_type" name="cp_type" value="">
        <input type="hidden" id="type" name="type" value="">
        <input type="hidden" id="cp_unit" name="cp_unit" value="">
        <input type="hidden" id="cp_unit_val" name="cp_unit_val" value="">

        <div class="row" style="clear: both">
            <button type="button" class="action-btn btn btn-primary btn-lg btn-block _close">关闭</button>
            <button style="margin-top: 2rem;" type="submit" class="action-btn btn btn-primary btn-lg btn-block">查询</button>
        </div>
    </form>

</div>
