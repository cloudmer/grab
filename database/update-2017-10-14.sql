ALTER TABLE `grab`.`custom_package`
DROP COLUMN `package_b`,
DROP COLUMN `package_a`,
ADD COLUMN `package` TEXT NOT NULL COMMENT '包A' AFTER `alias`;