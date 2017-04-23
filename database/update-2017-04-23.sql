CREATE TABLE IF NOT EXISTS `grab`.`tail` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `zero` TINYINT(3) NOT NULL COMMENT '0对应的下期开奖号码',
  `one` TINYINT(3) NOT NULL COMMENT '1对应下期的开奖号',
  `two` TINYINT(3) NOT NULL COMMENT '2对应的下棋开奖号',
  `three` TINYINT(3) NOT NULL COMMENT '3对应的下期开奖号',
  `four` TINYINT(3) NOT NULL COMMENT '4对应的下期开奖号',
  `five` TINYINT(3) NOT NULL COMMENT '5对应的下期开奖号码',
  `six` TINYINT(3) NOT NULL COMMENT '6对应的下期开奖号码',
  `seven` TINYINT(3) NOT NULL COMMENT '7对应的下期开奖号码',
  `eight` TINYINT(3) NOT NULL COMMENT '8对应的下期开奖号码',
  `nine` TINYINT(3) NOT NULL COMMENT '9对应的下期开奖号码',
  `continuity` TINYINT(5) NOT NULL COMMENT '连续多少期报警',
  `discontinuous` TINYINT(5) NOT NULL COMMENT '未连续多少期报警',
  `start` VARCHAR(45) NOT NULL,
  `end` VARCHAR(45) NOT NULL,
  `status` TINYINT(1) NOT NULL COMMENT '报警状态\n0=>关闭\n1=>开启',
  `time` INT(11) NOT NULL COMMENT '数据创建日期',
  PRIMARY KEY (`id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COMMENT = '尾号玩法';


ALTER TABLE `grab`.`tail`
CHANGE COLUMN `zero` `zero` CHAR(3) NOT NULL COMMENT '0对应的下期开奖号码' ,
CHANGE COLUMN `one` `one` CHAR(3) NOT NULL COMMENT '1对应下期的开奖号' ,
CHANGE COLUMN `two` `two` CHAR(3) NOT NULL COMMENT '2对应的下棋开奖号' ,
CHANGE COLUMN `three` `three` CHAR(3) NOT NULL COMMENT '3对应的下期开奖号' ,
CHANGE COLUMN `four` `four` CHAR(3) NOT NULL COMMENT '4对应的下期开奖号' ,
CHANGE COLUMN `five` `five` CHAR(3) NOT NULL COMMENT '5对应的下期开奖号码' ,
CHANGE COLUMN `six` `six` CHAR(3) NOT NULL COMMENT '6对应的下期开奖号码' ,
CHANGE COLUMN `seven` `seven` CHAR(3) NOT NULL COMMENT '7对应的下期开奖号码' ,
CHANGE COLUMN `eight` `eight` CHAR(3) NOT NULL COMMENT '8对应的下期开奖号码' ,
CHANGE COLUMN `nine` `nine` CHAR(3) NOT NULL COMMENT '9对应的下期开奖号码' ,
CHANGE COLUMN `continuity` `continuity` TINYINT(1) NOT NULL COMMENT '连续多少期报警' ,
CHANGE COLUMN `discontinuous` `discontinuous` TINYINT(1) NOT NULL COMMENT '未连续多少期报警' ,
CHANGE COLUMN `start` `start` CHAR(3) NOT NULL ,
CHANGE COLUMN `end` `end` CHAR(3) NOT NULL ;