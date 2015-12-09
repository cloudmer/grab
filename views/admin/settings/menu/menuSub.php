<?php
use app\models\Menus;
use yii\helpers\Url;
?>

<?php foreach($menuSub as $sub):?>
    <tr id="menu-<?= $sub->id?>">
        <td>
            <?= icon($sub->father_id2,$sub->father_id3); ?>
            <span class="<?= color($sub->father_id2,$sub->father_id3) ?>"><?= name($sub->father_id2,$sub->father_id3) ?></span>
        </td>
        <td>
            <span class="label label-default"><?= $sub->name?></span>
        </td>
        <td><i class="<?= $sub->icon?>"></i></td>
        <td><?= $sub->sort?></td>
        <td>
            <div class="onoffswitch">
                <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox is-show" id="<?= $sub->id ?>" data-url="<?= Url::to('/admin/settings/show') ?>" <?= $sub->state ? 'checked' : false ?> >
                <label class="onoffswitch-label" for="<?= $sub->id ?>"></label>
            </div>
        </td>
        <td>
            <a href="javascript: void(0)" class="table-link menu-active" data-url="<?= Url::to('/admin/settings/form') ?>" data-id="<?= $sub->id?>">
                <span class="fa-stack">
                    <i class="fa fa-square fa-stack-2x"></i>
                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                </span>
            </a>
            <a href="javascript: void(0)" class="table-link danger menu-delete" data-info="当前操作: <?= name($sub->father_id2,$sub->father_id3).' - ['.$sub->name.']' ?>" data-url="<?= Url::to('/admin/settings/delete') ?>" data-id="<?= $sub->id?>">
                <span class="fa-stack">
                    <i class="fa fa-square fa-stack-2x"></i>
                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                </span>
            </a>
        </td>
    </tr>

    <?php $menuSub3 = Menus::menuSub(3,$sub->id); if($menuSub3):?>
        <?= $this->render('menuSub',['menuSub'=>$menuSub3]);?>
    <?php endif ?>
<?php endforeach ?>