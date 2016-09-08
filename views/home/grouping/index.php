<meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no">
<?php
$script = <<< JS
$(document).ready(function(){
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
    .row input{
        margin: 5px 0 5px 0;
    }
    .row select{
        margin: 5px 0 5px 0;
    }
</style>

<div style="margin: 0 10px 0 10px">
    <div class="row">
        <form action="" method="post">
            <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
<!--                <input type="text" class="form-control"  name="appDate" id="appDate" placeholder="请选择查询时间">-->
                <input type="text" class="form-control"  name="date" id="appDate" placeholder="请选择查询时间">
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <select class="form-control" name="cp_type">
                    <option value="0" selected="">请选择彩票类型</option>
                    <option value="1">重庆时时彩</option>
                    <option value="2">天津时时彩</option>
                    <option value="3">新疆时时彩</option>
                </select>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <select class="form-control" name="cp_unit">
                    <option value="0" selected="">请选择分组单位</option>
                    <option value="1">万位</option>
                    <option value="2">千位</option>
                    <option value="3">百位</option>
                    <option value="3">十位</option>
                    <option value="3">个位</option>
                </select>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <select class="form-control" name="cp_unit_val">
                    <option value="0" selected="">请选择单位值</option>
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                </select>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <button type="submit" class="btn btn-primary btn-lg btn-block">查询</button>
            </div>
        </form>
    </div>
</div>

<?php if($error_msg): ?>
    <div class="alert alert-warning" role="alert" style="margin: 24px"><?php echo '警告：'.$error_msg.'!!! ' ?></div>
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

        <?= $this->render('_list',['model'=>$model,'type'=>$type,'unit'=>$unit])?>

        </tbody>
    </table>
<?php else: ?>

    <div class="text-center null" style="display: block; margin-bottom: 30px; margin-top: 40px;" data-page="2" data-url="/home/xjssc">
        <span style="padding: 9px 31px;color: black" class="label">没有更多数据了..</span>
    </div>

<?php endif; ?>



<div class="bottom_tools" style="bottom: 40px;">
    <a id="scrollUp" href="javascript:;" title="飞回顶部" style="display: block;"></a>
</div>
