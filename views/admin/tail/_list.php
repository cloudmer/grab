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

<?php foreach ($model as $m):?>
    <tr id="menu-<?= $m->id?>">
        <td> 所有彩种  </td>
        <td> <?= $m->zero ?>  </td>
        <td> <?= $m->one ?>  </td>
        <td> <?= $m->two ?>  </td>
        <td> <?= $m->three ?>  </td>
        <td> <?= $m->four ?>  </td>
        <td> <?= $m->five ?>  </td>
        <td> <?= $m->six ?>  </td>
        <td> <?= $m->seven ?>  </td>
        <td> <?= $m->eight ?>  </td>
        <td> <?= $m->nine ?>  </td>
        <td> <?= $m->continuity ?>  </td>
        <td> <?= $m->discontinuous ?>  </td>
        <td> <?= $m->start ?>  </td>
        <td> <?= $m->end ?>  </td>
        <td> <?= $m->status ? '开启' : '关闭'; ?>  </td>
        <td>

            <a href="<?= \yii\helpers\Url::to('/admin/tail/edit') ?>?id=<?= $m->id?>" class="table-link menu-active" >
                <span class="fa-stack">
                    <i class="fa fa-square fa-stack-2x"></i>
                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                </span>
            </a>

        </td>
    </tr>
<?php endforeach; ?>
