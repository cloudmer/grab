<?php
use app\models\Menus;
use yii\helpers\Url;

    function name($father_id2,$father_id3){
        if(!$father_id2 && !$father_id3){
            echo '顶级菜单';
        }
        if($father_id2 && !$father_id3){
            echo '二级菜单';
        }
        if($father_id3 && !$father_id2){
            echo '三级菜单';
        }
    }

    function color($father_id2,$father_id3){
        if(!$father_id2 && !$father_id3){
            echo 'label label-success';
        }
        if($father_id2 && !$father_id3){
            echo 'label label-info';
        }
        if($father_id3 && !$father_id2){
            echo 'label label-warning';
        }
    }

    function icon($father_id2,$father_id3){
        if($father_id2 && !$father_id3){
            echo '<i class="fa fa-paperclip"></i>';
        }
        if($father_id3 && !$father_id2){
            echo '&nbsp;&nbsp;&nbsp;<i class="fa fa-paperclip"></i>';
        }
    }

?>

<!--Ajax 添加菜单栏 返回当前添加的 html -->
<?php if(isset($model->id)): ?>
    <tr id="menu-<?= $model->id?>">
        <td>
            <?= icon($model->father_id2,$model->father_id3); ?>
            <span class="<?= color($model->father_id2,$model->father_id3) ?>"><?= name($model->father_id2,$model->father_id3) ?></span>
        </td>
        <td>
            <span class="label label-default"><?= $model->name?></span>
        </td>
        <td><i class="<?= $model->icon?>"></i></td>
        <td><?= $model->sort?></td>
        <td>
            <div class="onoffswitch">
                <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox is-show" id="<?= $model->id ?>" data-url="<?= Url::to('/admin/settings/show') ?>" <?= $model->state ? 'checked' : false ?> >
                <label class="onoffswitch-label" for="<?= $model->id ?>"></label>
            </div>
        </td>
        <td>
            <a href="javascript: void(0)" class="table-link menu-active" data-state="<?= !$model->state ? 0 : $model->state ?>" data-url="<?= Url::to('/admin/settings/form') ?>" data-id="<?= $model->id?>">
                <span class="fa-stack">
                    <i class="fa fa-square fa-stack-2x"></i>
                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                </span>
            </a>
            <a href="#" class="table-link danger menu-delete" data-info="当前操作: <?= name($model->father_id2,$model->father_id3).' - ['.$model->name.']' ?>" data-url="<?= Url::to('/admin/settings/delete') ?>" data-id="<?= $model->id?>">
                <span class="fa-stack">
                    <i class="fa fa-square fa-stack-2x"></i>
                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                </span>
            </a>
        </td>
    </tr>
<?php return; endif ?>

<!-- List 顶级菜单列表 -->
<?php foreach($model as $m): ?>
    <tr id="menu-<?= $m->id?>">
        <td><span class="<?= color($m->father_id2,$m->father_id3) ?>"><?= name($m->father_id2,$m->father_id3) ?></span></td>
        <td>
            <span class="label label-default"><?= $m->name?></span>
        </td>
        <td><i class="<?= $m->icon?>"></i></td>
        <td><?= $m->sort?></td>
        <td>
            <div class="onoffswitch">
                <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox is-show" id="<?= $m->id ?>" data-url="<?= Url::to('/admin/settings/show') ?>" <?= $m->state ? 'checked' : false ?> >
                <label class="onoffswitch-label" for="<?= $m->id ?>"></label>
            </div>
        </td>
        <td>
            <a href="javascript: void(0)" class="table-link menu-active" data-url="<?= Url::to('/admin/settings/form') ?>" data-id="<?= $m->id?>" >
                <span class="fa-stack">
                    <i class="fa fa-square fa-stack-2x"></i>
                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                </span>
            </a>
            <a href="javascript: void(0)" class="table-link danger menu-delete" data-info="当前操作: <?= name($m->father_id2,$m->father_id3).' - ['.$m->name.']' ?>" data-url="<?= Url::to('/admin/settings/delete') ?>" data-id="<?= $m->id?>" >
                <span class="fa-stack">
                    <i class="fa fa-square fa-stack-2x"></i>
                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                </span>
            </a>
        </td>
    </tr>

    <?php $menuSub = Menus::menuSub(2,$m->id); if($menuSub):?>
        <?= $this->render('menuSub',['menuSub'=>$menuSub]);?>
    <?php endif ?>

<?php endforeach ?>