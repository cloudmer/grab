<?php foreach($data as $obj) :?>
    <?php $contentArr = explode(',',$obj->txt);
          $contentArr = array_filter($contentArr);
          $contentArr = array_chunk($contentArr,7);
    ?>

    <?php foreach($contentArr as $val): ?>
        <tr>
            <?php foreach($val as $v): ?>
                <td><?= $v ?></td>
            <?php endforeach ?>
        </tr>
    <?php endforeach ?>
<?php endforeach ?>