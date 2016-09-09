<?php foreach ($model as $m) : ?>
    <tr>
        <td class="text-center"><?= $m->qishu ?></td>
        <td class="text-center"><?php echo $code = str_replace(" ", '', $m->code); ?></td>
        <td class="text-center"><span class="badge bg-gray" <?php echo !empty($m->analysisolds->front_three_lucky_txt) ? 'style="background: red"' : false;  ?> >  <?php echo !empty($m->analysisolds->front_three_lucky_txt) ? '中' : '未';  ?> </span></td>
        <td class="text-center"><span class="badge bg-gray" <?php echo !empty($m->analysisolds->center_three_lucky_txt) ? 'style="background: red"' : false;  ?>> <?php echo !empty($m->analysisolds->center_three_lucky_txt) ? '中' : '未';  ?> </span></td>
        <td class="text-center"><span class="badge bg-gray" <?php echo !empty($m->analysisolds->after_three_lucky_txt) ? 'style="background: red"' : false;  ?>> <?php echo !empty($m->analysisolds->after_three_lucky_txt) ? '中' : '未';  ?> </span></td>
    </tr>
<?php endforeach; ?>
