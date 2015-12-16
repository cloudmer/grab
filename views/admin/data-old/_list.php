<?php
if(!$data){
    return false;
}
$index = 0;
$content = str_replace("\r\n", ' ', $data->txt); //把换行符 替换成空格
$contentArr = explode(' ',$content);
$contentArr = array_filter($contentArr);
$contentArr = array_chunk($contentArr,3);
?>
<?php foreach($contentArr as $val): ?>
    <tr>
        <td><span class="label label-success">组-<?= $index = $index+1?></span></td>
        <?php foreach($val as $v): ?>
            <td><?= $v ?></td>
        <?php endforeach ?>
    </tr>
<?php endforeach ?>