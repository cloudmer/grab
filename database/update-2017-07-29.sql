ALTER TABLE `grab`.`packet`
ADD COLUMN `cycle` TINYINT(3) NOT NULL COMMENT '自定周期 \n如果连续多少期未中为1期' AFTER `time`,
ADD COLUMN `cycle_number` TINYINT(3) NOT NULL COMMENT '周期报警数' AFTER `cycle`;
