CREATE TABLE IF NOT EXISTS `grab`.`bjssc` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `qishu` CHAR(20) NOT NULL COMMENT '开奖期数',
  `one` TINYINT(3) NOT NULL COMMENT '万位 第一位号码',
  `two` TINYINT(3) NOT NULL COMMENT '千位 第二位号码',
  `three` TINYINT(3) NOT NULL COMMENT '百位 第三位号码',
  `four` TINYINT(3) NOT NULL COMMENT '十位 第四位号码',
  `five` TINYINT(3) NOT NULL COMMENT '个位 第五位号码',
  `code` CHAR(20) NOT NULL COMMENT '开奖号码',
  `front_three_type` TINYINT(1) NOT NULL COMMENT '前三类型\n1=>组6\n2=>组3',
  `center_three_type` TINYINT(1) NOT NULL COMMENT '中三类型\n1=>组6\n2=>组3',
  `after_three_type` TINYINT(1) NOT NULL COMMENT '后三类型\n1=>组6\n2=>组3',
  `kj_time` CHAR(50) NULL DEFAULT NULL COMMENT '开奖时间',
  `time` INT(11) NOT NULL COMMENT '数据记录时间',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = '北京时时彩';

CREATE TABLE IF NOT EXISTS `grab`.`analysisBjssc` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `bjssc_id` INT(11) NOT NULL COMMENT '重庆时时彩 数据分析表',
  `front_three_lucky_txt` TEXT NULL DEFAULT NULL COMMENT '前三 中奖号码',
  `front_three_regret_txt` TEXT NULL DEFAULT NULL COMMENT '前三 未中奖号码',
  `center_three_lucky_txt` TEXT NULL DEFAULT NULL COMMENT '中三中奖号',
  `center_three_regret_txt` TEXT NULL DEFAULT NULL COMMENT '中三未中奖号',
  `after_three_lucky_txt` TEXT NULL DEFAULT NULL COMMENT '后三中奖号码',
  `after_three_regret_txt` TEXT NULL DEFAULT NULL COMMENT '后三未中奖号码',
  `data_txt` TEXT NULL DEFAULT NULL COMMENT '当前导入的数据',
  `type` INT(11) NOT NULL COMMENT '重庆数据包id',
  `time` INT(11) NOT NULL COMMENT '数据创建日期',
  PRIMARY KEY (`id`),
  INDEX `fk_analysisBjssc_bjssc10_idx` (`bjssc_id` ASC),
  CONSTRAINT `fk_analysisBjssc_bjssc10`
    FOREIGN KEY (`bjssc_id`)
    REFERENCES `grab`.`bjssc` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = '北京时时彩分析';

CREATE TABLE IF NOT EXISTS `grab`.`bjdata` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `alias` CHAR(100) NOT NULL COMMENT '数据包别名',
  `data_txt` TEXT NOT NULL COMMENT '数据包内容',
  `start` CHAR(3) NOT NULL COMMENT '报警开始时间',
  `end` CHAR(3) NOT NULL COMMENT '报警结束时间',
  `regret_number` INT(11) NOT NULL COMMENT '邮件报警 几期未中奖报警',
  `forever` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '是否开启每一期中奖与未中奖通知 默认为关闭',
  `state` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '报警状态 默认关闭',
  `time` INT(11) NOT NULL COMMENT '数据创建时间',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = '北京时时彩数据包';

ALTER TABLE `grab`.`reserve`
  CHANGE COLUMN `cp_type` `cp_type` TINYINT(3) NOT NULL COMMENT '彩票类型\n1=>重庆时时彩\n2=>天津时时彩\n3=>新疆时时彩\n4=>北京时时彩' ;


ALTER TABLE `grab`.`analysisTjssc`
ADD CONSTRAINT `fk_analysisTjssc_tjssc1`
  FOREIGN KEY (`tjssc_id`)
  REFERENCES `grab`.`tjssc` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `grab`.`analysisXjssc`
ADD CONSTRAINT `fk_analysisXjssc_xjssc1`
  FOREIGN KEY (`xjssc_id`)
  REFERENCES `grab`.`xjssc` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `grab`.`analysisCqssc`
ADD CONSTRAINT `fk_analysisCqssc_cqssc1`
  FOREIGN KEY (`cqssc_id`)
  REFERENCES `grab`.`cqssc` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `grab`.`analysisBjssc`
ADD CONSTRAINT `fk_analysisBjssc_bjssc10`
  FOREIGN KEY (`bjssc_id`)
  REFERENCES `grab`.`bjssc` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


