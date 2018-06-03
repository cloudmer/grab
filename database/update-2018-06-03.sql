CREATE TABLE IF NOT EXISTS `alarm_record` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT  '自增主键',
  `alarm_id` INT(11) NOT NULL COMMENT  '2连号 报警id',
  `number` INT(11) NOT NULL COMMENT  '报警期数',
  `cycle` INT(11) NOT NULL COMMENT  '报警周期',
  `title` VARCHAR(250) NOT NULL COMMENT  '报警标题',
  `cp_type` INT(11) NOT NULL COMMENT  '彩票类型',
  `created_at` DATETIME NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COMMENT =  '2连号 报警记录';


ALTER TABLE `alarm_record`
ADD COLUMN `position` INT(11) NOT NULL COMMENT  '1=>前三，2=>中三, 3=>后三' AFTER `cp_type`;