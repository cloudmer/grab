<?php foreach ($model as $m) : ?>
    <tr>
        <td class="text-center"><?= $m->qishu ?></td>
        <td class="text-center"><?= $m->code ?></td>
        <td class="text-center">
            <?php if($type==1): ?>
                <span class="badge bg-gray" <?php echo !empty($m->analysisXjsscsData1->front_three_lucky_txt) ? 'style="background: red"' : false;  ?> >  <?php echo !empty($m->analysisXjsscsData1->front_three_lucky_txt) ? '中' : '未';  ?> </span>
            <?php else: ?>
                <span class="badge bg-gray" <?php echo !empty($m->analysisXjsscsData2->front_three_lucky_txt) ? 'style="background: red"' : false;  ?> >  <?php echo !empty($m->analysisXjsscsData2->front_three_lucky_txt) ? '中' : '未';  ?> </span>
            <?php endif;?>
        </td>
        <td class="text-center">
            <?php if($type==1): ?>
                <span class="badge bg-gray" <?php echo !empty($m->analysisXjsscsData1->center_three_lucky_txt) ? 'style="background: red"' : false;  ?>> <?php echo !empty($m->analysisXjsscsData1->center_three_lucky_txt) ? '中' : '未';  ?> </span>
            <?php else: ?>
                <span class="badge bg-gray" <?php echo !empty($m->analysisXjsscsData2->center_three_lucky_txt) ? 'style="background: red"' : false;  ?>> <?php echo !empty($m->analysisXjsscsData2->center_three_lucky_txt) ? '中' : '未';  ?> </span>
            <?php endif;?>
        </td>
        <td class="text-center">
            <?php if($type==1): ?>
                <span class="badge bg-gray" <?php echo !empty($m->analysisXjsscsData1->after_three_lucky_txt) ? 'style="background: red"' : false;  ?>> <?php echo !empty($m->analysisXjsscsData1->after_three_lucky_txt) ? '中' : '未';  ?> </span>
            <?php else: ?>
                <span class="badge bg-gray" <?php echo !empty($m->analysisXjsscsData2->after_three_lucky_txt) ? 'style="background: red"' : false;  ?>> <?php echo !empty($m->analysisXjsscsData2->after_three_lucky_txt) ? '中' : '未';  ?> </span>
            <?php endif;?>
        </td>
    </tr>
<?php endforeach; ?>
