<?php if($type == 'cq') :?>

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

            <?php $analysis = $m->getAnalysis($data_txt_id)->one(); ?>
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

<?php endif ?>

<?php if($type == 'tj') :?>

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
            <?php $analysis = $m->getAnalysis($data_txt_id)->one(); ?>
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

<?php endif ?>

<?php if($type == 'xj') :?>

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

            <?php $analysis = $m->getAnalysis($data_txt_id)->one(); ?>
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

<?php endif ?>


<?php if($type == 'bj') :?>

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

            <?php $analysis = $m->getAnalysis($data_txt_id)->one(); ?>
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

<?php endif ?>

