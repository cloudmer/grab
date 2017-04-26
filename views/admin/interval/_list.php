<?php
use yii\helpers\Url;

$csrf = Yii::$app->request->getCsrfToken();
$script = <<< JS
$(document).ready(function(){
    
});
JS;
$this->registerJs($script);
?>

<?php foreach ($model as $m):?>
    <tr id="menu-<?= $m->id?>">

        <td> 所有彩种  </td>
        <td><?= $m->number ?></td>
        <td> <?= $m->regret_number ?>  </td>
        <td> <?= $m->start ?>  </td>
        <td> <?= $m->end ?>  </td>
        <td> <?= $m->status ? '开启' : '关闭' ?>  </td>

        <td>
            <a href="<?= \yii\helpers\Url::to('/admin/interval/edit').'?id='. $m->id ?>" class="table-link menu-active" data-id="<?= $m->id?>" >
                <span class="fa-stack">
                    <i class="fa fa-square fa-stack-2x"></i>
                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                </span>
            </a>
            <a href="javascript: void(0)" class="table-link danger menu-delete" data-url="<?= \yii\helpers\Url::to('/admin/interval/delete') ?>" data-id="<?= $m->id?>" >
                <span class="fa-stack">
                    <i class="fa fa-square fa-stack-2x"></i>
                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                </span>
            </a>
        </td>
    </tr>
<?php endforeach; ?>
