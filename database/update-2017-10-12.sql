CREATE TABLE IF NOT EXISTS `grab`.`custom_package` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `alias` VARCHAR(250) NOT NULL COMMENT '别名',
  `package_a` TEXT NOT NULL COMMENT '包A',
  `package_b` TEXT NOT NULL COMMENT '包B',
  `status` TINYINT(1) NOT NULL COMMENT '报警状态\n0=>关闭\n1=>开启',
  `start` CHAR(3) NOT NULL,
  `end` CHAR(3) NOT NULL,
  `continuity` INT(11) NOT NULL COMMENT '连续N期为 为1次累加,\n超过连续这么多期 就为开',
  `number` TINYINT(3) NOT NULL COMMENT '报警期数',
  PRIMARY KEY (`id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COMMENT = '自定义包';