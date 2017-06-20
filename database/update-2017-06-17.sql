CREATE TABLE IF NOT EXISTS `grab`.`newcode` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `one` CHAR(3) NOT NULL,
  `two` CHAR(3) NOT NULL,
  `three` CHAR(3) NOT NULL,
  `four` CHAR(3) NOT NULL,
  `five` CHAR(3) NOT NULL,
  `type` TINYINT(1) NOT NULL COMMENT '1=>江西\n2=>广东\n3=>山东',
  PRIMARY KEY (`id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COMMENT = '新彩';

CREATE TABLE IF NOT EXISTS `grab`.`newcodedata` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `alias` VARCHAR(100) NOT NULL COMMENT '别名',
  `number` TINYINT(5) NOT NULL COMMENT '报警期数',
  `contents` TEXT NOT NULL COMMENT '包数据',
  `start` CHAR(3) NOT NULL COMMENT '报警开始时间',
  `end` CHAR(3) NOT NULL COMMENT '报警结束时间',
  `status` TINYINT(1) NOT NULL COMMENT '报警状态\n0=>关闭\n1=>开启',
  PRIMARY KEY (`id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COMMENT = '新彩数据包';

CREATE TABLE IF NOT EXISTS `grab`.`newcodeanalysis` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `lucky` TINYINT(1) NOT NULL COMMENT '0=>没中\n1=>中',
  `newcodedata_id` INT(11) NOT NULL COMMENT 'newcodedata 主键值关联ID',
  PRIMARY KEY (`id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COMMENT = '新彩解析结果';


ALTER TABLE `grab`.`newcode`
ADD COLUMN `qihao` VARCHAR(20) NOT NULL COMMENT '期号' AFTER `id`;

ALTER TABLE `grab`.`newcodeanalysis`
ADD COLUMN `newcode_id` INT(11) NULL DEFAULT NULL COMMENT 'newcode 主键ID 关联' AFTER `newcodedata_id`;

ALTER TABLE `grab`.`newcodedata`
ADD COLUMN `type` TINYINT(1) NOT NULL COMMENT '1=>江西\n2=>广东\n3=>山东' AFTER `status`

ALTER TABLE `grab`.`newcode`
ADD COLUMN `time` INT(11) NOT NULL COMMENT '抓取时间' AFTER `type`;


ALTER TABLE `grab`.`newcodedata`
ADD COLUMN `time` INT(11) NOT NULL COMMENT '时间' AFTER `type`;