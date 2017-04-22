CREATE TABLE IF NOT EXISTS `grab`.`packet` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `alias` CHAR(100) NOT NULL COMMENT '数据包别名',
  `data_txt` TEXT NOT NULL COMMENT '数据包内容',
  `start` CHAR(3) NOT NULL COMMENT '报警开始时间',
  `end` CHAR(3) NOT NULL COMMENT '报警结束时间',
  `regret_number` INT(11) NOT NULL COMMENT '邮件报警 几期未中奖报警',
  `forever` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '是否开启每一期中奖与未中奖通知 默认为关闭',
  `state` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '报警状态 默认关闭',
  `type` TINYINT(1) NOT NULL COMMENT '彩种类型\n1=>重庆时时彩\n2=>天津时时彩\n3=>新疆时时彩\n4=>台湾五分彩',
  `time` INT(11) NOT NULL COMMENT '数据创建时间',
  PRIMARY KEY (`id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COMMENT = '数据包报警';