/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50136
Source Host           : localhost:3306
Source Database       : betterlife

Target Server Type    : MYSQL
Target Server Version : 50136
File Encoding         : 65001

Date: 2010-10-12 16:57:29
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `bb_core_blog`
-- ----------------------------
DROP TABLE IF EXISTS `bb_core_blog`;
CREATE TABLE `bb_core_blog` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '任务编号',
  `userId` int(11) DEFAULT NULL COMMENT '用户编号',
  `name` varchar(200) DEFAULT NULL COMMENT '任务名称',
  `content` varchar(500) DEFAULT NULL COMMENT '任务描述',
  `commitTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '记录更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bb_core_blog
-- ----------------------------
INSERT INTO `bb_core_blog` VALUES ('1', '2', 'test', 'test', '2010-09-13 09:22:19');
INSERT INTO `bb_core_blog` VALUES ('2', '2', 'test', 'test', '2010-07-30 09:59:48');
INSERT INTO `bb_core_blog` VALUES ('3', '2', 'test', 'testaaa', '2010-07-30 10:03:44');
INSERT INTO `bb_core_blog` VALUES ('4', '1', '翻译', '将snoopy的第一分镜头干掉', '2010-09-10 14:00:54');
INSERT INTO `bb_core_blog` VALUES ('5', '1', '将优采的项目搞好', '将优采的项目搞好', '2010-09-10 14:07:31');
INSERT INTO `bb_core_blog` VALUES ('6', '1', '什么时候能够进行重构', '什么时候能够进行重构呢？', '2010-09-10 16:08:34');

-- ----------------------------
-- Table structure for `bb_core_comment`
-- ----------------------------
DROP TABLE IF EXISTS `bb_core_comment`;
CREATE TABLE `bb_core_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '评论编号',
  `userId` int(11) NOT NULL COMMENT '评论者编号',
  `comment` varchar(200) CHARACTER SET utf8 DEFAULT NULL COMMENT '评论',
  `blogId` int(11) NOT NULL COMMENT '工件编号',
  `commitTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bb_core_comment
-- ----------------------------
INSERT INTO `bb_core_comment` VALUES ('1', '2', 'asdasd', '3', '2010-08-27 22:58:58');
INSERT INTO `bb_core_comment` VALUES ('2', '2', 'Nobody', '3', '2010-09-04 11:32:51');
INSERT INTO `bb_core_comment` VALUES ('3', '2', 'Fine', '3', '2010-09-04 11:38:28');
INSERT INTO `bb_core_comment` VALUES ('4', '2', 'super', '3', '2010-09-04 11:40:34');
INSERT INTO `bb_core_comment` VALUES ('5', '2', 'winter', '3', '2010-09-04 11:43:59');
INSERT INTO `bb_core_comment` VALUES ('6', '2', 'autumn', '3', '2010-09-04 11:52:38');
INSERT INTO `bb_core_comment` VALUES ('7', '2', 'back home', '3', '2010-09-04 11:55:14');
INSERT INTO `bb_core_comment` VALUES ('8', '2', 'summer', '3', '2010-09-04 11:57:24');
INSERT INTO `bb_core_comment` VALUES ('9', '2', 'spring', '3', '2010-09-04 11:58:10');
INSERT INTO `bb_core_comment` VALUES ('10', '2', 'season', '3', '2010-09-04 11:58:54');
INSERT INTO `bb_core_comment` VALUES ('12', '1', '不急不急，休息一下', '4', '2010-09-10 14:02:25');
INSERT INTO `bb_core_comment` VALUES ('13', '1', '可以开始了', '4', '2010-09-10 14:28:23');
INSERT INTO `bb_core_comment` VALUES ('14', '1', '风云再起时', '6', '2010-09-10 16:08:53');

-- ----------------------------
-- Table structure for `bb_log_loguser`
-- ----------------------------
DROP TABLE IF EXISTS `bb_log_loguser`;
CREATE TABLE `bb_log_loguser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL COMMENT '用户编号',
  `type` enum('1','2','3') NOT NULL COMMENT '类型；枚举类型：提交工件，删除工件，评论',
  `content` varchar(200) DEFAULT NULL COMMENT '一般日志类型决定了内容；这一栏一般没有内容',
  `commitTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bb_log_loguser
-- ----------------------------

-- ----------------------------
-- Table structure for `bb_msg_msg`
-- ----------------------------
DROP TABLE IF EXISTS `bb_msg_msg`;
CREATE TABLE `bb_msg_msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '消息编号',
  `senderId` int(11) NOT NULL COMMENT '发送者用户编号',
  `receiverId` int(11) NOT NULL COMMENT '接收者用户编号',
  `senderName` varchar(200) NOT NULL COMMENT '发送者名称',
  `receiverName` varchar(200) NOT NULL COMMENT '接收者名称',
  `content` varchar(500) DEFAULT NULL COMMENT '发送内容',
  `state` enum('0','1') DEFAULT '0' COMMENT '枚举类型，未读，已读',
  `commitTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bb_msg_msg
-- ----------------------------

-- ----------------------------
-- Table structure for `bb_msg_notice`
-- ----------------------------
DROP TABLE IF EXISTS `bb_msg_notice`;
CREATE TABLE `bb_msg_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `senderId` int(11) NOT NULL COMMENT '管理员编号',
  `title` varchar(200) DEFAULT NULL COMMENT '标题',
  `content` varchar(1000) DEFAULT NULL COMMENT '通知内容',
  `seriesId` int(11) DEFAULT NULL COMMENT '动画编号',
  `episodeId` int(11) DEFAULT NULL COMMENT '剧集编号',
  `sceneId` int(11) DEFAULT NULL COMMENT '分镜头编号',
  `version` enum('0','1','2','3','4','5','6','7','8','9') DEFAULT '0' COMMENT '分阶段,枚举类型;\r\n0:未启动\r\n1:翻译,\r\n2:台本,\r\n3:动态台本,\r\n4:背景,\r\n5:动画,\r\n6:导演,\r\n7:品管,\r\n8:预合成,\r\n9:合成',
  `endtime` timestamp NULL DEFAULT NULL COMMENT '截至浏览时间',
  `commitTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '提交时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bb_msg_notice
-- ----------------------------

-- ----------------------------
-- Table structure for `bb_msg_re_usernotice`
-- ----------------------------
DROP TABLE IF EXISTS `bb_msg_re_usernotice`;
CREATE TABLE `bb_msg_re_usernotice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) DEFAULT NULL COMMENT '用户编号',
  `noticeId` int(11) DEFAULT NULL COMMENT '通知编号',
  `commitTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '提交时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bb_msg_re_usernotice
-- ----------------------------

-- ----------------------------
-- Table structure for `bb_user_department`
-- ----------------------------
DROP TABLE IF EXISTS `bb_user_department`;
CREATE TABLE `bb_user_department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `manager` varchar(100) DEFAULT NULL,
  `costcenter` int(11) DEFAULT NULL,
  `businessunit` varchar(50) DEFAULT NULL,
  `hrrep` varchar(100) DEFAULT NULL,
  `locationstreet` varchar(50) DEFAULT NULL,
  `locationcity` varchar(20) DEFAULT NULL,
  `locationstate` char(2) DEFAULT NULL,
  `locationzipcode` varchar(10) DEFAULT NULL,
  `budget` int(11) DEFAULT NULL,
  `actualexpenses` int(11) DEFAULT NULL,
  `estsalary` int(11) DEFAULT NULL,
  `actualsalary` int(11) DEFAULT NULL,
  `esttravel` int(11) DEFAULT NULL,
  `actualtravel` int(11) DEFAULT NULL,
  `estsupplies` int(11) DEFAULT NULL,
  `actualsupplies` int(11) DEFAULT NULL,
  `estcontractors` int(11) DEFAULT NULL,
  `actualcontractors` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bb_user_department
-- ----------------------------
INSERT INTO `bb_user_department` VALUES ('1', 'User Experience', 'Big Boss', '11234', 'Core Services', 'Sigmund Freud', '601 Townsend St.', 'San Francisco', 'CA', '94103', '395000', '412000', '375000', '375000', '10000', '15000', '10000', '12000', '0', '10000');
INSERT INTO `bb_user_department` VALUES ('2', 'Engineering', 'Bill Lumburg', '34523', 'Research and Development', 'Jane Doe', '345 Park Ave', 'San Jose', 'CA', '95110', '434000', '436000', '410000', '415000', '12000', '10000', '12000', '11000', '0', '0');
INSERT INTO `bb_user_department` VALUES ('3', 'Space Exploration', 'Zaphod Beeblebrox', '11456', 'Research and Development', 'Jane Doe', '345 Park Ave', 'San Jose', 'CA', '95110', '1625000', '1823000', '500000', '500000', '25000', '23000', '1100000', '1300000', '0', '0');
INSERT INTO `bb_user_department` VALUES ('4', 'Corporate', 'Bruce Chizen', '11111', 'None', 'Sigmund Freud', '345 Park Ave', 'San Jose', 'CA', '95110', '660000', '705000', '500000', '500000', '100000', '120000', '20000', '30000', '40000', '55000');
INSERT INTO `bb_user_department` VALUES ('5', 'Advanced Physics Research', 'Albert Einstein', '66555', 'Research and Development', 'Jane Doe', '345 Park Ave', 'San Jose', 'CA', '95110', '440000', '444000', '410000', '410000', '15000', '17000', '15000', '17000', '0', '0');
INSERT INTO `bb_user_department` VALUES ('6', 'Food Services', 'Bob Dole', '85225', 'Core Services', 'Jane Doe', '345 Park Ave', 'San Jose', 'CA', '95110', '115000', '113000', '50000', '40000', '0', '0', '50000', '48000', '15000', '25000');
INSERT INTO `bb_user_department` VALUES ('7', 'Product Marketing', 'Willy Loman', '55301', 'Corporate Marketing', 'Sigmund Freud', '601 Townsend St.', 'San Francisco', 'CA', '94103', '445000', '484000', '375000', '400000', '30000', '32000', '20000', '22000', '20000', '30000');

-- ----------------------------
-- Table structure for `bb_user_function`
-- ----------------------------
DROP TABLE IF EXISTS `bb_user_function`;
CREATE TABLE `bb_user_function` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '权限编号',
  `url` varchar(500) CHARACTER SET utf8 DEFAULT NULL COMMENT '允许访问的URL权限',
  `commitTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '提交时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bb_user_function
-- ----------------------------

-- ----------------------------
-- Table structure for `bb_user_re_rolefunction`
-- ----------------------------
DROP TABLE IF EXISTS `bb_user_re_rolefunction`;
CREATE TABLE `bb_user_re_rolefunction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roleId` int(11) DEFAULT NULL COMMENT '角色编号',
  `functionId` int(11) DEFAULT NULL COMMENT '权限编号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bb_user_re_rolefunction
-- ----------------------------

-- ----------------------------
-- Table structure for `bb_user_re_userrole`
-- ----------------------------
DROP TABLE IF EXISTS `bb_user_re_userrole`;
CREATE TABLE `bb_user_re_userrole` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roleId` int(11) NOT NULL COMMENT '角色编号',
  `userId` int(11) NOT NULL COMMENT '用户编号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bb_user_re_userrole
-- ----------------------------
INSERT INTO `bb_user_re_userrole` VALUES ('1', '1', '2');
INSERT INTO `bb_user_re_userrole` VALUES ('2', '2', '3');
INSERT INTO `bb_user_re_userrole` VALUES ('3', '2', '4');
INSERT INTO `bb_user_re_userrole` VALUES ('4', '3', '5');
INSERT INTO `bb_user_re_userrole` VALUES ('5', '2', '6');
INSERT INTO `bb_user_re_userrole` VALUES ('6', '2', '2');

-- ----------------------------
-- Table structure for `bb_user_role`
-- ----------------------------
DROP TABLE IF EXISTS `bb_user_role`;
CREATE TABLE `bb_user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '角色编号',
  `name` varchar(200) DEFAULT NULL COMMENT '角色名称',
  `commitTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bb_user_role
-- ----------------------------
INSERT INTO `bb_user_role` VALUES ('1', '主公', '2010-09-05 15:27:30');
INSERT INTO `bb_user_role` VALUES ('2', '忠臣', '2010-09-05 15:27:48');
INSERT INTO `bb_user_role` VALUES ('3', '奸贼', '2010-09-05 15:27:59');
INSERT INTO `bb_user_role` VALUES ('5', '内奸', '2010-09-05 15:28:20');
INSERT INTO `bb_user_role` VALUES ('4', '侠士', '2010-09-05 15:28:37');

-- ----------------------------
-- Table structure for `bb_user_user`
-- ----------------------------
DROP TABLE IF EXISTS `bb_user_user`;
CREATE TABLE `bb_user_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `departmentId` int(11) DEFAULT NULL COMMENT '门部编号',
  `name` varchar(200) NOT NULL COMMENT '用户名',
  `password` varchar(200) DEFAULT NULL COMMENT '用户密码',
  `commitTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '提交时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bb_user_user
-- ----------------------------
INSERT INTO `bb_user_user` VALUES ('1', '1', 'test', '098f6bcd4621d373cade4e832627b4f6', '2010-09-05 15:29:16');
INSERT INTO `bb_user_user` VALUES ('2', '5', '刘备', 'e10adc3949ba59abbe56e057f20f883e', '2010-09-10 13:14:16');
INSERT INTO `bb_user_user` VALUES ('3', '5', '张飞', 'e10adc3949ba59abbe56e057f20f883e', '2010-09-10 13:14:17');
INSERT INTO `bb_user_user` VALUES ('4', '5', '关羽', 'e10adc3949ba59abbe56e057f20f883e', '2010-09-10 13:14:19');
INSERT INTO `bb_user_user` VALUES ('5', '7', '曹操', 'e10adc3949ba59abbe56e057f20f883e', '2010-09-10 13:14:20');
INSERT INTO `bb_user_user` VALUES ('6', '5', '诸葛亮', 'e10adc3949ba59abbe56e057f20f883e', '2010-09-10 13:14:21');
INSERT INTO `bb_user_user` VALUES ('7', '5', 'wondercool', null, '2010-09-16 17:31:56');
INSERT INTO `bb_user_user` VALUES ('10', '5', 'joy', '098f6bcd4621d373cade4e832627b4f6', '2010-09-17 17:15:07');
INSERT INTO `bb_user_user` VALUES ('9', '5', 'joy', '098f6bcd4621d373cade4e832627b4f6', '2010-09-17 17:14:58');
INSERT INTO `bb_user_user` VALUES ('11', '5', 'joy', '098f6bcd4621d373cade4e832627b4f6', '2010-09-17 17:15:14');

-- ----------------------------
-- Table structure for `bb_user_userdetail`
-- ----------------------------
DROP TABLE IF EXISTS `bb_user_userdetail`;
CREATE TABLE `bb_user_userdetail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) DEFAULT NULL,
  `departmentId` int(11) DEFAULT NULL,
  `email` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `cellphone` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `commitTime` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bb_user_userdetail
-- ----------------------------
INSERT INTO `bb_user_userdetail` VALUES ('1', '2', null, null, '13333333333', null);
