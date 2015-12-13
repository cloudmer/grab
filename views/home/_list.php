<?php foreach($model as $m): ?>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><?= array_search(array_search($m->type,\app\models\Code::$codeType),\app\models\Code::$urlArr). '&nbsp;&nbsp;开奖期号:'.$m->qishu ?></h3>
        </div>
        <div class="panel-body">
            开奖号码:&nbsp;&nbsp;<?= $m->one.','.$m->two.','.$m->three.','.$m->four.','.$m->five ?>
            <br/>
            大小比例:&nbsp;&nbsp;<?= $m->size ?>
            <br/>
            奇偶比例:&nbsp;&nbsp;<?= $m->jiou ?>
            <br/>
            更新时间:&nbsp;&nbsp;<?= date('Y-m-d H:i:s',$m->time) ?>
            <?php foreach($m->analysis as $ana): ?>
                <?php if($ana->state == 1): ?>
                    <span class="label label-info">中奖号码:</span>&nbsp;&nbsp;
                    <span style="word-wrap: break-word;">
                        <?= $ana->code ?>
                    </span>
                    <br/>
                    当前导入数据:&nbsp;&nbsp;<?= $ana->data_txt?>
                <?php elseif($ana->state == 0): ?>
                    <span class="label label-warning">未中奖号码:</span>&nbsp;&nbsp;
                    <span style="word-wrap: break-word;">
                        <?= $ana->code ?>
                    </span>
                    <br/>
                    当前导入数据:&nbsp;&nbsp;
                    <span style="word-wrap: break-word;">
                        <?= $ana->data_txt?>
                    </span>
                <?php endif ?>
            <?php endforeach ?>
        </div>
    </div>
<?php endforeach ?>
