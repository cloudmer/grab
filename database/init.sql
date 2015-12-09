/*
Navicat MySQL Data Transfer

Source Server         : 本地数据库
Source Server Version : 50535
Source Host           : localhost:3306
Source Database       : aiyeyun

Target Server Type    : MYSQL
Target Server Version : 50535
File Encoding         : 65001

Date: 2015-11-25 12:59:59
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `menus`
-- ----------------------------
DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`father_id2`  int(11) NULL DEFAULT NULL COMMENT '二级菜单' ,
`father_id3`  int(11) NULL DEFAULT NULL COMMENT '三级菜单' ,
`name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '名称' ,
`icon`  varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '图标' ,
`controller`  varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '#' COMMENT '控制器' ,
`action`  varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '#' COMMENT '方法' ,
`state`  tinyint(3) NULL DEFAULT NULL COMMENT '排序' ,
`sort`  tinyint(3) NULL DEFAULT 0 COMMENT '状态' ,
`add_time`  int(11) NOT NULL COMMENT '添加时间' ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=5

;

-- ----------------------------
-- Records of menus
-- ----------------------------
BEGIN;
INSERT INTO `menus` VALUES ('1', null, null, '控制台', 'fa fa-dashboard', '/admin/manage', 'index', '1', null, '1448427092'), ('2', null, null, '聊天室', 'fa fa-user', '/admin/chat-room', 'index', '1', null, '1448427137'), ('3', null, null, '设置', 'fa fa-cog', '', '', '1', null, '1448427157'), ('4', '3', null, '菜单栏', null, '/admin/settings', 'menus', '1', null, '1448427187');
COMMIT;

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`username`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`password`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`auth_key`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`access_token`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`nick_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '昵称' ,
`head_portrait`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '头像' ,
`email`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '邮箱' ,
`phone`  varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '电话' ,
`sex`  tinyint(1) NULL DEFAULT NULL COMMENT '性别' ,
`age`  tinyint(3) NULL DEFAULT NULL COMMENT '年龄' ,
`role`  tinyint(3) NOT NULL DEFAULT 0 COMMENT '用户类型' ,
`state`  tinyint(3) NULL DEFAULT 0 COMMENT '状态' ,
`login_time`  int(11) NULL DEFAULT NULL COMMENT '最后登陆时间' ,
`login_ip`  varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '登陆IP' ,
PRIMARY KEY (`id`),
UNIQUE INDEX `auth_key_UNIQUE` (`auth_key`) USING BTREE ,
UNIQUE INDEX `access_token_UNIQUE` (`access_token`) USING BTREE 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=3

;

-- ----------------------------
-- Records of user
-- ----------------------------
BEGIN;
INSERT INTO `user` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', null, null, '夜云', '/upload/20151117/1447729585221.jpg', '644362887@qq.com', '15228883771', '1', '23', '1', null, '1448426069', '127.0.0.1'), ('2', 'yeyun', '21232f297a57a5a743894a0e4a801fc3', null, null, '夜风', '/upload/20151117/1447729557216.jpg', '644362887@qq.com', '15228883771', '1', '23', '1', null, '1447912739', '127.0.0.1');
COMMIT;

-- ----------------------------
-- Auto increment value for `menus`
-- ----------------------------
ALTER TABLE `menus` AUTO_INCREMENT=5;

-- ----------------------------
-- Auto increment value for `user`
-- ----------------------------
ALTER TABLE `user` AUTO_INCREMENT=3;
