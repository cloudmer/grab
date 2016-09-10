<tr id="menu-<?= $model->id?>">
    <td><?= $model->type ?></td>
    <td> <?= $model->cp_type ?>  </td>
    <td> <?= $model->code_type ?>  </td>
    <td> <?= $model->number ?>  </td>
    <td> <?= $model->qishu ?>  </td>
    <td> <?= $model->status ?>  </td>
    <td>
        <a href="javascript: void(0)" class="table-link menu-active" data-url="<?= \yii\helpers\Url::to('/admin/reserve/form') ?>" data-id="<?= $m->id?>" >
                <span class="fa-stack">
                    <i class="fa fa-square fa-stack-2x"></i>
                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                </span>
        </a>
        <a href="javascript: void(0)" class="table-link danger menu-delete" data-url="<?= \yii\helpers\Url::to('/admin/reserve/delete') ?>" data-id="<?= $m->id?>" >
                <span class="fa-stack">
                    <i class="fa fa-square fa-stack-2x"></i>
                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                </span>
        </a>
    </td>
</tr>