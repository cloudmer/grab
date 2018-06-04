<?php foreach ($model as $key => $m) : ?>
    <?php
        // 查询 报警期数号码
        $code = \app\models\Xjssc::findOne([ 'id' => $m['alarm_id'] ]);
        // 报警后的 下一期
        $nextCode = \app\models\Xjssc::findOne([ 'id' => $m['alarm_id'] + 1 ]);
    ?>
    <tr>
        <td class="text-center"><?= $code->qishu ?></td>
        <td class="text-center"><?php echo $code = str_replace(" ", '', $code->code); ?></td>
        <td class="text-center">
            <span class="badge bg-gray" <?= $m->position == \app\models\AlarmRecord::q3 ? 'style="background: red"' : false ?> >
                <?= $m->position == \app\models\AlarmRecord::q3 ? '报警' : '等待' ?>
            </span>
        </td>
        <td class="text-center">
            <span class="badge bg-gray" <?= $m->position == \app\models\AlarmRecord::z3 ? 'style="background: red"' : false ?> >
                <?= $m->position == \app\models\AlarmRecord::z3 ? '报警' : '等待' ?>
            </span>
        </td>
        <td class="text-center">
            <span class="badge bg-gray" <?= $m->position == \app\models\AlarmRecord::h3 ? 'style="background: red"' : false ?> >
                <?= $m->position == \app\models\AlarmRecord::h3 ? '报警' : '等待' ?>
            </span>
        </td>
    </tr>
    <?php if ($nextCode) : ?>
        <tr>
            <td class="text-center"><?= $nextCode->qishu ?></td>
            <td class="text-center"><?php echo $code = str_replace(" ", '', $nextCode->code); ?></td>
            <td class="text-center">
                <span class="badge bg-gray" >下一期</span>
            </td>
            <td class="text-center">
                <span class="badge bg-gray" >下一期</span>
            </td>
            <td class="text-center">
                <span class="badge bg-gray" >下一期</span>
            </td>
        </tr>
    <?php endif ?>
<?php endforeach; ?>
