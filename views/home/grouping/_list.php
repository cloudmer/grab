<?php if($type == 1) :?>

    <?php foreach ($model as $m) : ?>
        <?php $code = str_replace(" ", '', $m->code);?>
        <tr>
            <td class="text-center"><?= $m->qishu ?></td>
            <td class="text-center">

                <?php if($unit == 1 && $code[0] == $unit_val): ?>
                    <span class="badge bg-gray" style="background: red">
                        <?php echo $code[0] ?>
                    </span>
                <?php else: ?>
                    <?php echo $code[0] ?>
                <?php endif; ?>

                <?php if($unit == 2 && $code[1] == $unit_val): ?>
                    <span class="badge bg-gray" style="background: red">
                        <?php echo $code[1] ?>
                    </span>
                <?php else: ?>
                    <?php echo $code[1] ?>
                <?php endif; ?>

                <?php if($unit == 3 && $code[2] == $unit_val): ?>
                    <span class="badge bg-gray" style="background: red">
                        <?php echo $code[2] ?>
                    </span>
                <?php else: ?>
                    <?php echo $code[2] ?>
                <?php endif; ?>

                <?php if($unit == 4 && $code[3] == $unit_val): ?>
                    <span class="badge bg-gray" style="background: red">
                        <?php echo $code[3] ?>
                    </span>
                <?php else: ?>
                    <?php echo $code[3] ?>
                <?php endif; ?>

                <?php if($unit == 5 && $code[4] == $unit_val): ?>
                    <span class="badge bg-gray" style="background: red">
                        <?php echo $code[4] ?>
                    </span>
                <?php else: ?>
                    <?php echo $code[4] ?>
                <?php endif; ?>


            </td>
            <td class="text-center"><span class="badge bg-gray" <?php echo !empty($m->analysisolds->front_three_lucky_txt) ? 'style="background: red"' : false;  ?> >  <?php echo !empty($m->analysisTjsscs->front_three_lucky_txt) ? '中' : '未';  ?> </span></td>
            <td class="text-center"><span class="badge bg-gray" <?php echo !empty($m->analysisolds->center_three_lucky_txt) ? 'style="background: red"' : false;  ?>> <?php echo !empty($m->analysisTjsscs->center_three_lucky_txt) ? '中' : '未';  ?> </span></td>
            <td class="text-center"><span class="badge bg-gray" <?php echo !empty($m->analysisolds->after_three_lucky_txt) ? 'style="background: red"' : false;  ?>> <?php echo !empty($m->analysisTjsscs->after_three_lucky_txt) ? '中' : '未';  ?> </span></td>
        </tr>
    <?php endforeach; ?>

<?php endif ?>

<?php if($type == 2) :?>

    <?php foreach ($model as $m) : ?>
        <tr>
            <td class="text-center"><?= $m->qishu ?></td>
            <td class="text-center">

                <?php if($unit == 1 && $m->one == $unit_val): ?>
                    <span class="badge bg-gray" style="background: red">
                        <?php echo $m->one ?>
                    </span>
                <?php else: ?>
                    <?php echo $m->one ?>
                <?php endif; ?>

                <?php if($unit == 2 && $m->two == $unit_val): ?>
                    <span class="badge bg-gray" style="background: red">
                        <?php echo $m->two ?>
                    </span>
                <?php else: ?>
                    <?php echo $m->two ?>
                <?php endif; ?>

                <?php if($unit == 3 && $m->three == $unit_val): ?>
                    <span class="badge bg-gray" style="background: red">
                        <?php echo $m->three ?>
                    </span>
                <?php else: ?>
                    <?php echo $m->three ?>
                <?php endif; ?>

                <?php if($unit == 4 && $m->four == $unit_val): ?>
                    <span class="badge bg-gray" style="background: red">
                        <?php echo $m->one ?>
                    </span>
                <?php else: ?>
                    <?php echo $m->four ?>
                <?php endif; ?>

                <?php if($unit == 5 && $m->five == $unit_val): ?>
                    <span class="badge bg-gray" style="background: red">
                        <?php echo $m->five ?>
                    </span>
                <?php else: ?>
                    <?php echo $m->five ?>
                <?php endif; ?>


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

            <td class="text-center">
                <?php if($unit == 1 && $m->one == $unit_val): ?>
                    <span class="badge bg-gray" style="background: red">
                        <?php echo $m->one ?>
                    </span>
                <?php else: ?>
                    <?php echo $m->one ?>
                <?php endif; ?>

                <?php if($unit == 2 && $m->two == $unit_val): ?>
                    <span class="badge bg-gray" style="background: red">
                        <?php echo $m->two ?>
                    </span>
                <?php else: ?>
                    <?php echo $m->two ?>
                <?php endif; ?>

                <?php if($unit == 3 && $m->three == $unit_val): ?>
                    <span class="badge bg-gray" style="background: red">
                        <?php echo $m->three ?>
                    </span>
                <?php else: ?>
                    <?php echo $m->three ?>
                <?php endif; ?>

                <?php if($unit == 4 && $m->four == $unit_val): ?>
                    <span class="badge bg-gray" style="background: red">
                        <?php echo $m->one ?>
                    </span>
                <?php else: ?>
                    <?php echo $m->four ?>
                <?php endif; ?>

                <?php if($unit == 5 && $m->five == $unit_val): ?>
                    <span class="badge bg-gray" style="background: red">
                        <?php echo $m->five ?>
                    </span>
                <?php else: ?>
                    <?php echo $m->five ?>
                <?php endif; ?>
            </td>

            <td class="text-center"><span class="badge bg-gray" <?php echo !empty($m->analysisXjsscs->front_three_lucky_txt) ? 'style="background: red"' : false;  ?> >  <?php echo !empty($m->analysisXjsscs->front_three_lucky_txt) ? '中' : '未';  ?> </span></td>
            <td class="text-center"><span class="badge bg-gray" <?php echo !empty($m->analysisXjsscs->center_three_lucky_txt) ? 'style="background: red"' : false;  ?>> <?php echo !empty($m->analysisXjsscs->center_three_lucky_txt) ? '中' : '未';  ?> </span></td>
            <td class="text-center"><span class="badge bg-gray" <?php echo !empty($m->analysisXjsscs->after_three_lucky_txt) ? 'style="background: red"' : false;  ?>> <?php echo !empty($m->analysisXjsscs->after_three_lucky_txt) ? '中' : '未';  ?> </span></td>
        </tr>
    <?php endforeach; ?>

<?php endif ?>


