ALTER TABLE `grab`.`configure`
CHANGE COLUMN `type` `type` TINYINT(1) NOT NULL COMMENT '1 新时时彩\n2 重庆时时彩\n3 天津时时彩\n4 新疆时时彩' ;

INSERT INTO `grab`.`configure` (`id`, `start_time`, `end_time`, `regret_number`, `forever`, `state`, `type`) VALUES ('3', '09', '23', '3', '0', '1', '3');
INSERT INTO `grab`.`configure` (`id`, `start_time`, `end_time`, `regret_number`, `forever`, `state`, `type`) VALUES ('4', '09', '23', '3', '0', '1', '4');





CREATE TABLE IF NOT EXISTS `grab`.`tjssc` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `qishu` INT(11) NOT NULL COMMENT '开奖期数',
  `one` TINYINT(3) NOT NULL COMMENT '万位 第一位号码',
  `two` TINYINT(3) NOT NULL COMMENT '千位 第二位号码',
  `three` TINYINT(3) NOT NULL COMMENT '百位 第三位号码',
  `four` TINYINT(3) NOT NULL COMMENT '十位 第四位号码',
  `five` TINYINT(3) NOT NULL COMMENT '个位 第五位号码',
  `code` CHAR(20) NOT NULL COMMENT '开奖号码',
  `kj_time` CHAR(50) NULL DEFAULT NULL COMMENT '开奖时间',
  `time` INT(11) NOT NULL COMMENT '数据记录时间',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = '天津时时彩';

CREATE TABLE IF NOT EXISTS `grab`.`analysisTjssc` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `tjssc_id` INT(11) NOT NULL COMMENT '天津时时彩开奖表主键id',
  `front_three_lucky_txt` TEXT NULL DEFAULT NULL COMMENT '前三 中奖号码',
  `front_three_regret_txt` TEXT NULL DEFAULT NULL COMMENT '前三 未中奖号码',
  `center_three_lucky_txt` TEXT NULL DEFAULT NULL COMMENT '中三中奖号',
  `center_three_regret_txt` TEXT NULL DEFAULT NULL COMMENT '中三未中奖号',
  `after_three_lucky_txt` TEXT NULL DEFAULT NULL COMMENT '后三中奖号码',
  `after_three_regret_txt` TEXT NULL DEFAULT NULL COMMENT '后三未中奖号码',
  `data_txt` TEXT NULL DEFAULT NULL COMMENT '当前导入的数据',
  `time` INT(11) NOT NULL COMMENT '数据创建日期',
  PRIMARY KEY (`id`),
  INDEX `fk_analysisTjssc_tjssc1_idx` (`tjssc_id` ASC),
  CONSTRAINT `fk_analysisTjssc_tjssc1`
    FOREIGN KEY (`tjssc_id`)
    REFERENCES `grab`.`tjssc` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = '天津时时彩分析';

CREATE TABLE IF NOT EXISTS `grab`.`xjssc` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `qishu` INT(11) NOT NULL COMMENT '开奖时间',
  `one` TINYINT(3) NOT NULL COMMENT '万位 第一位号码',
  `two` TINYINT(3) NOT NULL COMMENT '千位 第二位号码',
  `three` TINYINT(3) NOT NULL COMMENT '百位 第三位号码',
  `four` TINYINT(3) NOT NULL COMMENT '十位 第四位号码',
  `five` TINYINT(3) NOT NULL COMMENT '个位 第五位号码',
  `code` CHAR(20) NOT NULL COMMENT '开奖号码',
  `kj_time` CHAR(50) NULL DEFAULT NULL COMMENT '开奖时间',
  `time` INT(11) NOT NULL COMMENT '数据记录时间',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = '新疆时时彩';

CREATE TABLE IF NOT EXISTS `grab`.`analysisXjssc` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `xjssc_id` INT(11) NOT NULL COMMENT '新疆时时彩开奖表主键ID',
  `front_three_lucky_txt` TEXT NULL DEFAULT NULL COMMENT '前三 中奖号码',
  `front_three_regret_txt` TEXT NULL DEFAULT NULL COMMENT '前三 未中奖号码',
  `center_three_lucky_txt` TEXT NULL DEFAULT NULL COMMENT '中三中奖号',
  `center_three_regret_txt` TEXT NULL DEFAULT NULL COMMENT '中三未中奖号',
  `after_three_lucky_txt` TEXT NULL DEFAULT NULL COMMENT '后三中奖号码',
  `after_three_regret_txt` TEXT NULL DEFAULT NULL COMMENT '后三未中奖号码',
  `data_txt` TEXT NULL DEFAULT NULL COMMENT '当前导入的数据',
  `time` INT(11) NOT NULL COMMENT '数据创建日期',
  PRIMARY KEY (`id`),
  INDEX `fk_analysisXjssc_xjssc1_idx` (`xjssc_id` ASC),
  CONSTRAINT `fk_analysisXjssc_xjssc1`
    FOREIGN KEY (`xjssc_id`)
    REFERENCES `grab`.`xjssc` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = '新疆时时彩数据分析';

CREATE TABLE IF NOT EXISTS `grab`.`cqssc` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `qishu` INT(11) NOT NULL COMMENT '开奖期数',
  `one` TINYINT(3) NOT NULL COMMENT '万位 第一位号码',
  `two` TINYINT(3) NOT NULL COMMENT '千位 第二位号码',
  `three` TINYINT(3) NOT NULL COMMENT '百位 第三位号码',
  `four` TINYINT(3) NOT NULL COMMENT '十位 第四位号码',
  `five` TINYINT(3) NOT NULL COMMENT '个位 第五位号码',
  `code` CHAR(20) NOT NULL COMMENT '开奖号码',
  `kj_time` CHAR(50) NULL DEFAULT NULL COMMENT '开奖时间',
  `time` INT(11) NOT NULL COMMENT '数据记录时间',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = '重庆时时彩';

CREATE TABLE IF NOT EXISTS `grab`.`analysisCqssc` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `cqssc_id` INT(11) NOT NULL COMMENT '重庆时时彩 数据分析表',
  `front_three_lucky_txt` TEXT NULL DEFAULT NULL COMMENT '前三 中奖号码',
  `front_three_regret_txt` TEXT NULL DEFAULT NULL COMMENT '前三 未中奖号码',
  `center_three_lucky_txt` TEXT NULL DEFAULT NULL COMMENT '中三中奖号',
  `center_three_regret_txt` TEXT NULL DEFAULT NULL COMMENT '中三未中奖号',
  `after_three_lucky_txt` TEXT NULL DEFAULT NULL COMMENT '后三中奖号码',
  `after_three_regret_txt` TEXT NULL DEFAULT NULL COMMENT '后三未中奖号码',
  `data_txt` TEXT NULL DEFAULT NULL COMMENT '当前导入的数据',
  `time` INT(11) NOT NULL COMMENT '数据创建日期',
  PRIMARY KEY (`id`),
  INDEX `fk_analysisCqssc_cqssc1_idx` (`cqssc_id` ASC),
  CONSTRAINT `fk_analysisCqssc_cqssc1`
    FOREIGN KEY (`cqssc_id`)
    REFERENCES `grab`.`cqssc` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = '重庆时时彩分析';


ALTER TABLE `grab`.`tjssc`
CHANGE COLUMN `qishu` `qishu` CHAR(20) NOT NULL COMMENT '开奖期数' ;

ALTER TABLE `grab`.`xjssc`
CHANGE COLUMN `qishu` `qishu` CHAR(20) NOT NULL COMMENT '开奖期数' ;

ALTER TABLE `grab`.`cqssc`
CHANGE COLUMN `qishu` `qishu` CHAR(20) NOT NULL COMMENT '开奖期数' ;


CREATE TABLE IF NOT EXISTS `grab`.`reserve` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `type` TINYINT(3) NOT NULL COMMENT '报警单位\n1=>不论前中后3\n2=>前3\n3=>中3\n4=>后3',
  `cp_type` TINYINT(3) NOT NULL COMMENT '彩票类型\n1=>重庆时时彩\n2=>天津时时彩\n3=>新疆时时彩',
  `code_type` TINYINT(3) NOT NULL COMMENT '奖号类型\n1=>组6\n2=>组3',
  `number` TINYINT(1) NOT NULL COMMENT '预定号码',
  `qishu` INT(11) NOT NULL COMMENT '几期不开，则报警，直到开了为止',
  `status` TINYINT(3) NOT NULL DEFAULT 1 COMMENT '报警状态\n默认开启\n0=>关闭\n1=>开启\n',
  `time` INT(11) NOT NULL COMMENT '数据创建日期',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = '预定号码，不开则报警配置';


ALTER TABLE `grab`.`tjssc`
ADD COLUMN `front_three_type` TINYINT(1) NOT NULL COMMENT '前三类型\n1=>组6\n2=>组3' AFTER `code`,
ADD COLUMN `center_three_type` TINYINT(1) NOT NULL COMMENT '中三类型\n1=>组6\n2=>组3' AFTER `front_three_type`,
ADD COLUMN `after_three_type` TINYINT(1) NOT NULL COMMENT '后三类型\n1=>组6\n2=>组3' AFTER `center_three_type`;

ALTER TABLE `grab`.`xjssc`
ADD COLUMN `front_three_type` TINYINT(1) NOT NULL COMMENT '前三类型\n1=>组6\n2=>组3' AFTER `code`,
ADD COLUMN `center_three_type` TINYINT(1) NOT NULL COMMENT '中三类型\n1=>组6\n2=>组3' AFTER `front_three_type`,
ADD COLUMN `after_three_type` TINYINT(1) NOT NULL COMMENT '后三类型\n1=>组6\n2=>组3' AFTER `center_three_type`;

ALTER TABLE `grab`.`cqssc`
ADD COLUMN `front_three_type` TINYINT(1) NOT NULL COMMENT '前三类型\n1=>组6\n2=>组3' AFTER `code`,
ADD COLUMN `center_three_type` TINYINT(1) NOT NULL COMMENT '中三类型\n1=>组6\n2=>组3' AFTER `front_three_type`,
ADD COLUMN `after_three_type` TINYINT(1) NOT NULL COMMENT '后三类型\n1=>组6\n2=>组3' AFTER `center_three_type`;

ALTER TABLE `grab`.`reserve`
CHANGE COLUMN `number` `number` TINYINT(3) NOT NULL COMMENT '预定号码' ;

ALTER TABLE `grab`.`reserve`
DROP COLUMN `code_type`;


ALTER TABLE `grab`.`comparison`
CHANGE COLUMN `type` `type` TINYINT(10) NOT NULL COMMENT '1表示新时时彩\n2表示重庆时时彩\n22表示重庆时时彩数据包2\n3表示天津时时彩\n33表示重庆时时彩数据包2\n4表示新疆时时彩\n44表示新疆时时彩数据包2' ,
COMMENT = '数据导入 表' ;