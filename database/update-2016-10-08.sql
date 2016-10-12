CREATE TABLE IF NOT EXISTS `grab`.`cqdata` (
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
COMMENT = '重庆时时彩数据包';

CREATE TABLE IF NOT EXISTS `grab`.`tjdata` (
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
COMMENT = '天津时时彩数据包';

CREATE TABLE IF NOT EXISTS `grab`.`xjdata` (
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
COMMENT = '新疆时时彩数据包';

ALTER TABLE `grab`.`analysisTjssc`
CHANGE COLUMN `type` `type` INT(11) NOT NULL COMMENT '天津数据包id' ,
ADD INDEX `fk_analysisTjssc_tjssc1_idx` (`tjssc_id` ASC),
DROP INDEX `fk_analysisTjssc_tjssc1_idx` ;

ALTER TABLE `grab`.`analysisXjssc`
CHANGE COLUMN `type` `type` INT(11) NOT NULL COMMENT '新疆数据包id' ,
ADD INDEX `fk_analysisXjssc_xjssc1_idx` (`xjssc_id` ASC),
DROP INDEX `fk_analysisXjssc_xjssc1_idx` ;

ALTER TABLE `grab`.`analysisCqssc`
CHANGE COLUMN `type` `type` INT(11) NOT NULL COMMENT '重庆数据包id' ,
ADD INDEX `fk_analysisCqssc_cqssc1_idx` (`cqssc_id` ASC),
DROP INDEX `fk_analysisCqssc_cqssc1_idx` ;


