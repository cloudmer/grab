<table id="table-example-fixed" class="table table-hover">
    <thead>
    <tr>
        <th>号组</th>
        <th>号(1)</th>
        <th>号(2)</th>
        <th>号(3)</th>
    </tr>
    </thead>
    <tbody>

    <?php
    $index = 0;
    $content = str_replace("\r\n", ' ', $data); //把换行符 替换成空格
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

    </tbody>
</table>