CREATE TABLE IF NOT EXISTS `grab`.`interval` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `number` CHAR(6) NOT NULL COMMENT '号码',
  `regret_number` INT(11) NOT NULL COMMENT '报警期数',
  `status` TINYINT(1) NOT NULL COMMENT '报警状态\n0=>关闭\n1=>开启',
  `start` CHAR(3) NOT NULL COMMENT '报警开始时间',
  `end` CHAR(3) NOT NULL COMMENT '报警结束时间',
  `time` INT(11) NOT NULL COMMENT '数据创建时间',
  PRIMARY KEY (`id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COMMENT = '间隔玩法';