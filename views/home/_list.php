<?php
$this->registerJsFile('/js/fileUpload/ajaxfileupload.js');
$script = <<< JS
$(document).ready(function(){
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
})
JS;
$this->registerJs($script);
?>

<?php foreach($model as $m): ?>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><?= array_search(array_search($m->type,\app\models\Code::$codeType),\app\models\Code::$urlArr). '&nbsp;&nbsp;开奖期号:'.$m->qishu ?></h3>
        </div>
        <div class="panel-body">
            开奖号码:&nbsp;&nbsp;<?= $m->one.','.$m->two.','.$m->three.','.$m->four.','.$m->five ?>
            <br/>
            大小比例:&nbsp;&nbsp;<?= $m->size ?>
            <br/>
            奇偶比例:&nbsp;&nbsp;<?= $m->jiou ?>
            <br/>
            是否中奖:&nbsp;&nbsp;
            <?php if(!empty($m->analysis->lucky_txt)) :?>
                <san class="badge" style="background: red">中</san>
            <?php else : ?>
                <san class="badge">没中</san>
            <?php endif ?>
            <br/>
            更新时间:&nbsp;&nbsp;<?= date('Y-m-d H:i:s',$m->time) ?>
            <br/>

            <div style="margin-top: 10px">
                <a class="btn-block text-center" style='text-decoration:none; margin-top: 10px' role="button" data-toggle="collapse" href="#collapseExample-data-<?= $m->id ?>" aria-expanded="true" aria-controls="collapseExample">
                    <span class="label label-info">点击查看 - 当前导入数据</span>
                </a>
                <div class="collapse" id="collapseExample-data-<?= $m->id ?>" style="margin-top: 10px">
                    <div class="well text-center">
                        <?= $m->analysis->data_txt ? str_replace(PHP_EOL, '<br/>', $m->analysis->data_txt) : '当前暂无数据导入' ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?php endforeach ?>
