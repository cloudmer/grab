CREATE TABLE IF NOT EXISTS `grab`.`newcodeinterval` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `contents` TEXT NOT NULL,
  `alias` VARCHAR(100) NOT NULL COMMENT '数据包别名',
  `start` CHAR(3) NOT NULL,
  `end` CHAR(3) NOT NULL,
  `time` INT(11) NOT NULL,
  `status` TINYINT(1) NOT NULL COMMENT '0=>关闭\n1=>开启',
  `type` TINYINT(3) NOT NULL COMMENT '1=>江西\n2=>广东\n3=>山东',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = '11选5 间隔包'

ALTER TABLE `grab`.`newcodeinterval`
ADD COLUMN `number` TINYINT(5) NOT NULL AFTER `type`;