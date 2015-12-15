<?php
$csrf = Yii::$app->request->getCsrfToken();
$type = Yii::$app->request->get('type') ? Yii::$app->request->get('type') : 1;
$script = <<< JS
$(document).ready(function(){
     $(".more").click(function(){
        var d = dialog({fixed: true}).show();
        var _this = $(this);
        _this.hide();
        var page = $(this).attr('data-page');
        var url = $(this).attr('data-url');
        $.get(url,{type:$type,page:page},function(data){
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
});
JS;
$this->registerJs($script);
?>

<div class="container">
    <div class="contents">
        <?= $this->render('_list',['model'=>$model])?>
    </div>

    <div class="text-center more" data-page="2" data-url="<?= \yii\helpers\Url::to('/home/index')?>">
        <span style="padding: 9px 31px;" class="label label-success">加载更多</span>
    </div>

    <div class="text-center null" style="display: none" data-page="2" data-url="<?= \yii\helpers\Url::to('/home/index')?>">
        <span style="padding: 9px 31px;color: black" class="label">没有更多数据了..</span>
    </div>

</div>
