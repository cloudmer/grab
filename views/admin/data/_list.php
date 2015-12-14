<?php $index = 0; ?>
<?php foreach($data as $obj) :?>
    <?php
        $content = str_replace(PHP_EOL, ' ', $obj->txt); //把换行符 替换成空格
        $contentArr = explode(' ',$content);
        $contentArr = array_filter($contentArr);
        $contentArr = array_chunk($contentArr,5);
    ?>
    <?php foreach($contentArr as $val): ?>
        <tr>
            <td><span class="label label-success">组-<?= $index = $index+1?></span></td>
            <?php foreach($val as $v): ?>
                <td><?= $v ?></td>
            <?php endforeach ?>
        </tr>
    <?php endforeach ?>
<?php endforeach ?>