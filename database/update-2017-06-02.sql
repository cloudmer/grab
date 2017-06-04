CREATE TABLE IF NOT EXISTS `grab`.`double` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `alias` VARCHAR(100) NOT NULL COMMENT '别名',
  `package_a` TEXT NOT NULL,
  `package_b` TEXT NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '报警\n0 = > 关闭\n1 = > 开启',
  `start` CHAR(3) NOT NULL,
  `end` CHAR(3) NOT NULL,
  `number` TINYINT(3) NOT NULL COMMENT '报警期数',
  PRIMARY KEY (`id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COMMENT = '双包玩法';