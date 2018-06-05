<?php
function code_sort($code){
    $ary = [];
    for ($i=0; $i< strlen($code); $i++) {
        $ary[$i] = $code[$i];
    }

    // 是否是组三 组三不管
    $ary = array_unique($ary);
    if (count($ary) != 3) {
        return false;
    }

    sort($ary);
    $strCode = '';
    foreach ($ary as $val) {
        $strCode .= $val;
    }
    return $strCode;
}

//是否是连续的号码 并返回 最小的连号 例如: 123 是连号 返回最新的2个连号 将 返回 12
function isConsecutiveNumber($code){
    if ($code == false) {
        return false;
    }
    $center_bool = false;
	$tail_bool = false;

	$first_int = $code[0];
    $center_int = $code[1];
	$tail_int = $code[2];

    //检查 下标 0 与 下标 1 是否是连号
    if ($first_int + 1 == $center_int) {
        $center_bool = true;
	}

    //检查 下标 1 与 下标 2 是否是连号
    if ($center_int + 1 == $tail_int) {
        $tail_bool = true;
	}

    //下标0 与下标1 是连号 并且 下标1 与下标2 是连号 将返回最小的2个连号
    if ($center_bool == true && $tail_bool == true) {
        $front = $code[0] + $code[1];
        $after = $code[1] + $code[2];

		if ($front < $after ) {
            return $code[0] + $code[1];
		}
		return $code[1] + $code[2];
	}

    //下标0 与下标1 是连号
    if ($center_bool == true) {
        return $code[0] + $code[1];
	}

    //下标1 与下标2 是连号
    if ($tail_bool == true) {
        return $code[1] + $code[2];
	}
    return false;
}

//是否包含其中一位号码
function consecutiveContainNumber($code, $val) {
    if ($code == false) {
        return false;
    }
    $num = 0;
    for ($i=0; $i<strlen($val); $i++) {
        $str = $val[$i];
        if (strpos($code,$str)) {
            $num += 1;
        }
    }
    return $num;
}

?>

<?php foreach ($model as $key => $m) : ?>
    <?php
        // 查询 报警期数号码
        $code = \app\models\Cqssc::findOne([ 'id' => $m['alarm_id'] ]);

        // 前三号码
        $q3_code = code_sort(str_replace(" ", '', $code->one.$code->two.$code->three));
        // 中三号码
        $z3_code = code_sort(str_replace(" ", '', $code->two.$code->three.$code->four));
        // 后三号码
        $h3_code = code_sort(str_replace(" ", '', $code->three.$code->four.$code->five));

        // 本期是否有 2连号出现
        $q3_consecutive = isConsecutiveNumber($q3_code);
        $z3_consecutive = isConsecutiveNumber($z3_code);
        $h3_consecutive = isConsecutiveNumber($h3_code);

        // 报警后的 下一期
        $nextCode = \app\models\Cqssc::findOne([ 'id' => $m['alarm_id'] + 1 ]);
    ?>
    <tr>
        <td class="text-center"><?= $code->qishu ?></td>
        <td class="text-center"><?php echo $code = str_replace(" ", '', $code->code); ?></td>
        <td class="text-center">
            <span class="badge bg-gray" <?= $m->position == \app\models\AlarmRecord::q3 ? 'style="background: red"' : false ?> >
                <?= $m->position == \app\models\AlarmRecord::q3 ? '报警' : $m->q_num.'期' ?>
            </span>
        </td>
        <td class="text-center">
            <span class="badge bg-gray" <?= $m->position == \app\models\AlarmRecord::z3 ? 'style="background: red"' : false ?> >
                <?= $m->position == \app\models\AlarmRecord::z3 ? '报警' : $m->z_num.'期' ?>
            </span>
        </td>
        <td class="text-center">
            <span class="badge bg-gray" <?= $m->position == \app\models\AlarmRecord::h3 ? 'style="background: red"' : false ?> >
                <?= $m->position == \app\models\AlarmRecord::h3 ? '报警' : $m->h_num.'期' ?>
            </span>
        </td>
    </tr>
    <?php if ($nextCode) : ?>
        <?php
        // 前三号码
        $next_q3_code = code_sort(str_replace(" ", '', $nextCode->one.$nextCode->two.$nextCode->three));
        // 中三号码
        $next_z3_code = code_sort(str_replace(" ", '', $nextCode->two.$nextCode->three.$nextCode->four));
        // 后三号码
        $next_h3_code = code_sort(str_replace(" ", '', $nextCode->three.$nextCode->four.$nextCode->five));

        //上一期出现了 连号 检查本期号码 是否包含上期的连号 的 其中一位
        $q3_luck = consecutiveContainNumber($next_q3_code, $q3_consecutive);
        $z3_luck = consecutiveContainNumber($next_z3_code, $z3_consecutive);
        $h3_luck = consecutiveContainNumber($next_h3_code, $h3_consecutive);
        ?>
        <tr>
            <td class="text-center"><?= $nextCode->qishu ?></td>
            <td class="text-center"><?php echo $code = str_replace(" ", '', $nextCode->code); ?></td>
            <td class="text-center">
                <span class="badge bg-gray" <?= $q3_luck ? 'style="background: blue"' : false ?> >
                    <?= $q3_luck ? '中' : '未'; ?>
                </span>
            </td>
            <td class="text-center">
                <span class="badge bg-gray" <?= $z3_luck ? 'style="background: blue"' : false ?> >
                    <?= $z3_luck ? '中' : '未'; ?>
                </span>
            </td>
            <td class="text-center">
                <span class="badge bg-gray" <?= $h3_luck ? 'style="background: blue"' : false ?> >
                    <?= $h3_luck ? '中' : '未'; ?>
                </span>
            </td>
        </tr>
    <?php endif ?>
<?php endforeach; ?>
