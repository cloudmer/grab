<?php foreach ($model as $m) : ?>
    <?php $analysis = $m->getAnalysis($type)->one(); ?>
    <tr>
        <td class="text-center"><?= $m->qishu ?></td>
        <td class="text-center"><?php echo $code = str_replace(" ", '', $m->code); ?></td>
        <td class="text-center">
            <span class="badge bg-gray" <?php echo !empty($analysis->front_three_lucky_txt) ? 'style="background: red"' : false;  ?> >  <?php echo !empty($analysis->front_three_lucky_txt) ? '中' : '未';  ?> </span>
        </td>
        <td class="text-center">
            <span class="badge bg-gray" <?php echo !empty($analysis->center_three_lucky_txt) ? 'style="background: red"' : false;  ?> >  <?php echo !empty($analysis->center_three_lucky_txt) ? '中' : '未';  ?> </span>
        </td>
        <td class="text-center">
            <span class="badge bg-gray" <?php echo !empty($analysis->after_three_lucky_txt) ? 'style="background: red"' : false;  ?> >  <?php echo !empty($analysis->after_three_lucky_txt) ? '中' : '未';  ?> </span>
        </td>
    </tr>
<?php endforeach; ?>
