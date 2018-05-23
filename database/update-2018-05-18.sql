CREATE TABLE IF NOT EXISTS `txffc` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `qishu` CHAR(20) NOT NULL COMMENT  '开奖期数',
  `one` TINYINT(3) NOT NULL COMMENT  '万位 第一位号码',
  `two` TINYINT(3) NOT NULL COMMENT  '千位 第二位号码',
  `three` TINYINT(3) NOT NULL COMMENT  '百位 第三位号码',
  `four` TINYINT(3) NOT NULL COMMENT  '十位 第四位号码',
  `five` TINYINT(3) NOT NULL COMMENT  '个位 第五位号码',
  `code` CHAR(20) NOT NULL COMMENT  '开奖号码',
  `front_three_type` TINYINT(1) NOT NULL COMMENT  '前三类型
  1=>组6
2=>组3',
  `center_three_type` TINYINT(1) NOT NULL COMMENT  '中三类型
1=>组6
2=>组3',
  `after_three_type` TINYINT(1) NOT NULL COMMENT  '后三类型
1=>组6
2=>组3',
  `kj_time` CHAR(50) NULL DEFAULT NULL COMMENT  '开奖时间',
  `time` INT(11) NOT NULL COMMENT  '数据记录时间',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT =  '腾讯分分彩';

CREATE TABLE IF NOT EXISTS `analysisTxffc` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `txffc_id` INT(11) NOT NULL COMMENT  '腾讯分分彩 数据分析表',
  `front_three_lucky_txt` TEXT NULL DEFAULT NULL COMMENT  '前三 中奖号码',
  `front_three_regret_txt` TEXT NULL DEFAULT NULL COMMENT  '前三 未中奖号码',
  `center_three_lucky_txt` TEXT NULL DEFAULT NULL COMMENT  '中三中奖号',
  `center_three_regret_txt` TEXT NULL DEFAULT NULL COMMENT  '中三未中奖号',
  `after_three_lucky_txt` TEXT NULL DEFAULT NULL COMMENT  '后三中奖号码',
  `after_three_regret_txt` TEXT NULL DEFAULT NULL COMMENT  '后三未中奖号码',
  `data_txt` TEXT NULL DEFAULT NULL COMMENT  '当前导入的数据',
  `type` INT(11) NOT NULL COMMENT  '腾讯数据包id',
  `time` INT(11) NOT NULL COMMENT  '数据创建日期',
  PRIMARY KEY (`id`),
  INDEX `fk_analysisCqssc_cqssc10_idx` (`txffc_id` ASC),
  CONSTRAINT `fk_analysisCqssc_cqssc10`
    FOREIGN KEY (`txffc_id`)
    REFERENCES `txffc` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT =  '腾讯分分彩';

CREATE TABLE IF NOT EXISTS `txdata` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `alias` CHAR(100) NOT NULL COMMENT  '数据包别名',
  `data_txt` TEXT NOT NULL COMMENT  '数据包内容',
  `start` CHAR(3) NOT NULL COMMENT  '报警开始时间',
  `end` CHAR(3) NOT NULL COMMENT  '报警结束时间',
  `regret_number` INT(11) NOT NULL COMMENT  '邮件报警 几期未中奖报警',
  `forever` TINYINT(1) NOT NULL DEFAULT 0 COMMENT  '是否开启每一期中奖与未中奖通知 默认为关闭',
  `state` TINYINT(1) NOT NULL DEFAULT 0 COMMENT  '报警状态 默认关闭',
  `time` INT(11) NOT NULL COMMENT  '数据创建时间',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT =  '腾讯分分彩数据包';

ALTER TABLE `packet`
CHANGE COLUMN `type` `type` TINYINT(1) NOT NULL COMMENT  '彩种类型
1=>重庆时时彩
2=>天津时时彩
3=>新疆时时彩
4=>台湾五分彩
5=>腾讯分分彩';