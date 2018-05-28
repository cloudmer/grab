CREATE TABLE IF NOT EXISTS `ssc_cycle` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT  '自增主键',
  `alias` VARCHAR(100) NOT NULL COMMENT  '别名',
  `data_txt` text DEFAULT NULL COMMENT  '数据包',
  `start` CHAR(2) NOT NULL COMMENT  '报警开始时间',
  `end` CHAR(2) NOT NULL COMMENT  '报警结束时间',
  `continuity` INT(11) NOT NULL COMMENT  'A包连续',
  `cycle` INT(11) NOT NULL COMMENT  '周期数',
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COMMENT =  '时时彩 与 腾讯分分彩的 a 连续 周期';

ALTER TABLE `ssc_cycle`
ADD COLUMN `status` TINYINT(1) NOT NULL COMMENT  '报警状态' AFTER `continuity`;