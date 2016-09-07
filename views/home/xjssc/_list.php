<?php foreach ($model as $m) : ?>
    <tr>
        <td class="text-center"><?= $m->qishu ?></td>
        <td class="text-center"><?= $m->code ?></td>
        <td class="text-center"><span class="badge bg-gray" <?php echo !empty($m->analysisXjsscs->front_three_lucky_txt) ? 'style="background: red"' : false;  ?> >  <?php echo !empty($m->analysisXjsscs->front_three_lucky_txt) ? '中' : '未';  ?> </span></td>
        <td class="text-center"><span class="badge bg-gray" <?php echo !empty($m->analysisXjsscs->center_three_lucky_txt) ? 'style="background: red"' : false;  ?>> <?php echo !empty($m->analysisXjsscs->center_three_lucky_txt) ? '中' : '未';  ?> </span></td>
        <td class="text-center"><span class="badge bg-gray" <?php echo !empty($m->analysisXjsscs->after_three_lucky_txt) ? 'style="background: red"' : false;  ?>> <?php echo !empty($m->analysisXjsscs->after_three_lucky_txt) ? '中' : '未';  ?> </span></td>
    </tr>
<?php endforeach; ?>
