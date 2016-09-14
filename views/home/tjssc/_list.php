<?php foreach ($model as $m) : ?>
    <tr>
        <td class="text-center"><?= $m->qishu ?></td>
        <td class="text-center"><?= $m->code ?></td>
        <td class="text-center">
            <?php if($type==1): ?>
                <span class="badge bg-gray" <?php echo !empty($m->analysisTjsscsData1->front_three_lucky_txt) ? 'style="background: red"' : false;  ?> >  <?php echo !empty($m->analysisTjsscsData1->front_three_lucky_txt) ? '中' : '未';  ?> </span>
            <?php else: ?>
                <span class="badge bg-gray" <?php echo !empty($m->analysisTjsscsData2->front_three_lucky_txt) ? 'style="background: red"' : false;  ?> >  <?php echo !empty($m->analysisTjsscsData2->front_three_lucky_txt) ? '中' : '未';  ?> </span>
            <?php endif;?>
        </td>
        <td class="text-center">
            <?php if($type==1): ?>
                <span class="badge bg-gray" <?php echo !empty($m->analysisTjsscsData1->center_three_lucky_txt) ? 'style="background: red"' : false;  ?>> <?php echo !empty($m->analysisTjsscsData1->center_three_lucky_txt) ? '中' : '未';  ?> </span>
            <?php else: ?>
                <span class="badge bg-gray" <?php echo !empty($m->analysisTjsscsData2->center_three_lucky_txt) ? 'style="background: red"' : false;  ?>> <?php echo !empty($m->analysisTjsscsData2->center_three_lucky_txt) ? '中' : '未';  ?> </span>
            <?php endif;?>
        </td>
        <td class="text-center">
            <?php if($type==1): ?>
                <span class="badge bg-gray" <?php echo !empty($m->analysisTjsscsData1->after_three_lucky_txt) ? 'style="background: red"' : false;  ?>> <?php echo !empty($m->analysisTjsscsData1->after_three_lucky_txt) ? '中' : '未';  ?> </span>
            <?php else: ?>
                <span class="badge bg-gray" <?php echo !empty($m->analysisTjsscsData2->after_three_lucky_txt) ? 'style="background: red"' : false;  ?>> <?php echo !empty($m->analysisTjsscsData2->after_three_lucky_txt) ? '中' : '未';  ?> </span>
            <?php endif;?>
        </td>
    </tr>
<?php endforeach; ?>
