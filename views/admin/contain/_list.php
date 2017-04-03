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
});
JS;
$this->registerJs($script);
?>

<!--Ajax 添加菜单栏 返回当前添加的 html -->
<?php if(isset($model->id)): ?>
    <tr id="menu-<?= $model->id?>">
        <td> <?= \app\models\Contain::$get_cp_type[$model->cp_type] ?>  </td>
        <td><?= $model->contents ?></td>
        <td> <?= $model->number ?>  </td>
        <td> <?= $model->start ?>  </td>
        <td> <?= $model->end ?>  </td>
        <td> <?= \app\models\Contain::$get_status[$model->valve] ?>  </td>
        <td>
            <a href="javascript: void(0)" class="table-link menu-active" data-url="<?= \yii\helpers\Url::to('/admin/contain/form') ?>" data-id="<?= $model->id?>" >
                <span class="fa-stack">
                    <i class="fa fa-square fa-stack-2x"></i>
                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                </span>
            </a>
            <a href="javascript: void(0)" class="table-link danger menu-delete" data-url="<?= \yii\helpers\Url::to('/admin/contain/delete') ?>" data-id="<?= $model->id?>" >
                <span class="fa-stack">
                    <i class="fa fa-square fa-stack-2x"></i>
                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                </span>
            </a>
        </td>
    </tr>
    <?php return; endif ?>
<?php ?>


<?php foreach ($model as $m):?>
    <tr id="menu-<?= $m->id?>">

        <td> <?= \app\models\Contain::$get_cp_type[$m->cp_type] ?>  </td>
        <td><?= $m->contents ?></td>
        <td> <?= $m->number ?>  </td>
        <td> <?= $m->start ?>  </td>
        <td> <?= $m->end ?>  </td>
        <td> <?= \app\models\Contain::$get_status[$m->valve] ?>  </td>

        <td>
            <a href="javascript: void(0)" class="table-link menu-active" data-url="<?= \yii\helpers\Url::to('/admin/contain/form') ?>" data-id="<?= $m->id?>" >
                <span class="fa-stack">
                    <i class="fa fa-square fa-stack-2x"></i>
                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                </span>
            </a>
            <a href="javascript: void(0)" class="table-link danger menu-delete" data-url="<?= \yii\helpers\Url::to('/admin/contain/delete') ?>" data-id="<?= $m->id?>" >
                <span class="fa-stack">
                    <i class="fa fa-square fa-stack-2x"></i>
                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                </span>
            </a>
        </td>
    </tr>
<?php endforeach; ?>
