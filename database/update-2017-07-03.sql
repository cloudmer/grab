CREATE TABLE IF NOT EXISTS `grab`.`alarm` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `number` INT(11) NOT NULL COMMENT '报警期数',
  `start` CHAR(3) NOT NULL,
  `end` CHAR(3) NOT NULL,
  `status` TINYINT(1) NOT NULL COMMENT '报警状态\n0=>关闭\n1=>开启',
  `type` INT(11) NOT NULL COMMENT '类型\n1=>连号报警类型',
  `time` INT(11) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COMMENT = '报警提示 非数据包';