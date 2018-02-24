CREATE TABLE IF NOT EXISTS `play2` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `number` INT(11) NOT NULL COMMENT '报警期数',
  `start` CHAR(3) NOT NULL,
  `end` CHAR(3) NOT NULL,
  `status` TINYINT(1) NOT NULL COMMENT  '报警状态
  0=>关闭
1=>开启',
  `type` INT(11) NOT NULL COMMENT  '类型
1=>连号报警类型
0=>非连续报警类型',
  `time` INT(11) NOT NULL,
  `cycle` INT(11) NOT NULL COMMENT '周期，1个number 为一个周期累加值',
  PRIMARY KEY (`id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COMMENT =  '间隔几连号';