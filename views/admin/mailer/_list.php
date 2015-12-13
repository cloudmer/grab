<?php if($type=='ajax'): ?>
    <li class="clearfix" id="mailer-<?= $data->id?>">
        <div class="name">
            <div>
                <label>
                    <span class="label label-success">账号:</span> <?= $data->email_address ?>
                    <?php if($data->type==0): ?>
                        <span class="label label-warning">密码:</span> <?= $data->password ?>
                    <?php endif ?>
                </label>
            </div>
        </div>
        <div class="actions">
            <a href="javascript:void(0)" class="mailer-active table-link" data-id="<?= $data->id ?>" data-type="<?= $data->type?>" data-url="<?= \yii\helpers\Url::to('/admin/mailer/from')?>">
                <i class="fa fa-pencil"></i>
            </a>
            <a href="javascript:void(0)" class="mailer-delete table-link danger" data-id="<?= $data->id ?>" data-type="<?= $data->type?>" data-url="<?= \yii\helpers\Url::to('/admin/mailer/delete')?>">
                <i class="fa fa-trash-o"></i>
            </a>
        </div>
    </li>
<?php return; endif ?>

<?php $type == 'recipients' ? $model = $recipients : $model = $sender ?>
<?php foreach($model as $m): ?>
<li class="clearfix" id="mailer-<?= $m->id?>">
    <div class="name">
        <div>
            <label>
                <span class="label label-success">账号:</span> <?= $m->email_address ?>
                <?php if($m->type==0): ?>
                    <span class="label label-warning">密码:</span> <?= $m->password ?>
                <?php endif ?>
            </label>
        </div>
    </div>
    <div class="actions">
        <a href="javascript:void(0)" class="mailer-active table-link" data-id="<?= $m->id ?>" data-type="<?= $m->type?>" data-url="<?= \yii\helpers\Url::to('/admin/mailer/from')?>">
            <i class="fa fa-pencil"></i>
        </a>
        <a href="javascript:void(0)" class="mailer-delete table-link danger" data-id="<?= $m->id ?>" data-type="<?= $m->type?>" data-url="<?= \yii\helpers\Url::to('/admin/mailer/delete')?>">
            <i class="fa fa-trash-o"></i>
        </a>
    </div>
</li>
<?php endforeach ?>