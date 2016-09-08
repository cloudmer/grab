<?php if($type == 1) :?>

<?php endif ?>

<?php if($type == 2) :?>

    <?php foreach ($model as $m) : ?>
        <tr>
            <td class="text-center"><?= $m->qishu ?></td>
            <td class="text-center">

                <?php if($unit != 1): ?>
                    <?php echo $m->one ?>
                <?php else : ?>
                    <span class="badge bg-gray" style="background: red">
                        <?php echo $m->one ?>
                    </span>
                <?php endif;?>

                <?php if($unit != 2): ?>
                    <?php echo $m->two ?>
                <?php else : ?>
                    <span class="badge bg-gray" style="background: red">
                        <?php echo $m->two ?>
                    </span>
                <?php endif;?>

                <?php if($unit != 3): ?>
                    <?php echo $m->three ?>
                <?php else : ?>
                    <span class="badge bg-gray" style="background: red">
                        <?php echo $m->three ?>
                    </span>
                <?php endif;?>

                <?php if($unit != 4): ?>
                    <?php echo $m->four ?>
                <?php else : ?>
                    <span class="badge bg-gray" style="background: red">
                        <?php echo $m->four ?>
                    </span>
                <?php endif;?>

                <?php if($unit != 5): ?>
                    <?php echo $m->five ?>
                <?php else : ?>
                    <span class="badge bg-gray" style="background: red">
                        <?php echo $m->five ?>
                    </span>
                <?php endif;?>

            </td>
            <td class="text-center"><span class="badge bg-gray" <?php echo !empty($m->analysisTjsscs->front_three_lucky_txt) ? 'style="background: red"' : false;  ?> >  <?php echo !empty($m->analysisTjsscs->front_three_lucky_txt) ? '中' : '未';  ?> </span></td>
            <td class="text-center"><span class="badge bg-gray" <?php echo !empty($m->analysisTjsscs->center_three_lucky_txt) ? 'style="background: red"' : false;  ?>> <?php echo !empty($m->analysisTjsscs->center_three_lucky_txt) ? '中' : '未';  ?> </span></td>
            <td class="text-center"><span class="badge bg-gray" <?php echo !empty($m->analysisTjsscs->after_three_lucky_txt) ? 'style="background: red"' : false;  ?>> <?php echo !empty($m->analysisTjsscs->after_three_lucky_txt) ? '中' : '未';  ?> </span></td>
        </tr>
    <?php endforeach; ?>

<?php endif ?>

<?php if($type == 3) :?>

    <?php foreach ($model as $m) : ?>
        <tr>
            <td class="text-center"><?= $m->qishu ?></td>
            <td class="text-center"><?= $m->code ?></td>
            <td class="text-center"><span class="badge bg-gray" <?php echo !empty($m->analysisXjsscs->front_three_lucky_txt) ? 'style="background: red"' : false;  ?> >  <?php echo !empty($m->analysisXjsscs->front_three_lucky_txt) ? '中' : '未';  ?> </span></td>
            <td class="text-center"><span class="badge bg-gray" <?php echo !empty($m->analysisXjsscs->center_three_lucky_txt) ? 'style="background: red"' : false;  ?>> <?php echo !empty($m->analysisXjsscs->center_three_lucky_txt) ? '中' : '未';  ?> </span></td>
            <td class="text-center"><span class="badge bg-gray" <?php echo !empty($m->analysisXjsscs->after_three_lucky_txt) ? 'style="background: red"' : false;  ?>> <?php echo !empty($m->analysisXjsscs->after_three_lucky_txt) ? '中' : '未';  ?> </span></td>
        </tr>
    <?php endforeach; ?>

<?php endif ?>


