<?php foreach($model as $m): ?>
    <?php $m->type==2 ? $class = 'alert-warning' : $class = 'alert-success'?>
    <div class="alert alert-block <?= $class?>">
        <?= $m->content?>&nbsp;&nbsp;&nbsp;&nbsp;时间:<?= date('Y-m-d H:i:s',$m->time)?>
    </div>
<?php endforeach ?>