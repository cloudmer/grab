<?php foreach ($model as $m) : ?>
    <tr>
        <td class="text-center"><?= $m->qishu ?></td>
        <td class="text-center"><?php echo $code = str_replace(" ", '', $m->code); ?></td>
        <td class="text-center">
            <?php if($type==1): ?>
                <span class="badge bg-gray" <?php echo !empty($m->analysisCqsscsData1->front_three_lucky_txt) ? 'style="background: red"' : false;  ?> >  <?php echo !empty($m->analysisCqsscsData1->front_three_lucky_txt) ? '中' : '未';  ?> </span>
            <?php else: ?>
                <span class="badge bg-gray" <?php echo !empty($m->analysisCqsscsData2->front_three_lucky_txt) ? 'style="background: red"' : false;  ?> >  <?php echo !empty($m->analysisCqsscsData2->front_three_lucky_txt) ? '中' : '未';  ?> </span>
            <?php endif;?>
        </td>
        <td class="text-center">
            <?php if($type==1): ?>
                <span class="badge bg-gray" <?php echo !empty($m->analysisCqsscsData1->center_three_lucky_txt) ? 'style="background: red"' : false;  ?>> <?php echo !empty($m->analysisCqsscsData1->center_three_lucky_txt) ? '中' : '未';  ?> </span>
            <?php else: ?>
                <span class="badge bg-gray" <?php echo !empty($m->analysisCqsscsData2->center_three_lucky_txt) ? 'style="background: red"' : false;  ?>> <?php echo !empty($m->analysisCqsscsData2->center_three_lucky_txt) ? '中' : '未';  ?> </span>
            <?php endif;?>

        </td>
        <td class="text-center">
            <?php if($type==1): ?>
                <span class="badge bg-gray" <?php echo !empty($m->analysisCqsscsData1->after_three_lucky_txt) ? 'style="background: red"' : false;  ?>> <?php echo !empty($m->analysisCqsscsData1->after_three_lucky_txt) ? '中' : '未';  ?> </span>
            <?php else: ?>
                <span class="badge bg-gray" <?php echo !empty($m->analysisCqsscsData2->after_three_lucky_txt) ? 'style="background: red"' : false;  ?>> <?php echo !empty($m->analysisCqsscsData2->after_three_lucky_txt) ? '中' : '未';  ?> </span>
            <?php endif;?>
        </td>
    </tr>
<?php endforeach; ?>
