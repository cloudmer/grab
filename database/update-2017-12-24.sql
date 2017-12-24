CREATE TABLE IF NOT EXISTS `grab`.`play1` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `alias` VARCHAR(100) NOT NULL COMMENT  '别名',
  `package_a` TEXT NOT NULL COMMENT '包a',
  `package_b` TEXT NOT NULL COMMENT  '包b',
  `status` TINYINT(1) NOT NULL DEFAULT 0 COMMENT  '报警 0 = > 关闭 1 = > 开启',
  `start` CHAR(3) NOT NULL COMMENT  '报价开始时间',
  `end` CHAR(3) NOT NULL COMMENT  '报价结束时间',
  `continuity_number` TINYINT(3) NOT NULL COMMENT  '连续几b',
  `number` TINYINT(3) NOT NULL COMMENT  '报警期数',
  PRIMARY KEY (`id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COMMENT =  'a出现几期的b 的玩法'