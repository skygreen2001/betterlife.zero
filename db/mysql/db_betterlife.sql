-- ----------------------------
-- Table structure for `bb_core_blog`
-- ----------------------------
DROP TABLE IF EXISTS `bb_core_blog`;
CREATE TABLE `bb_core_blog` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '博客编号',
  `userId` int(11) NOT NULL COMMENT '用户编号',
  `name` varchar(200) DEFAULT NULL COMMENT '博客名称',
  `content` varchar(500) DEFAULT NULL COMMENT '博客内容',
  `commitTime` timestamp NULL DEFAULT NULL COMMENT '记录更新时间',
  `updateTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`,`userId`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='博客';

-- ----------------------------
-- Records of bb_core_blog
-- ----------------------------
INSERT INTO `bb_core_blog` VALUES ('1', '1', 'Web在线编辑器', '搜索关键字：在线编辑器\r\n引自：<a href=\"http://paranimage.com/22-online-web-editor/\" target=\"_blank\">http://paranimage.com/22-online-web-editor/</a>', '2011-01-06 16:04:20', '2011-05-26 14:39:36');
INSERT INTO `bb_core_blog` VALUES ('2', '1', '地图导航第三方库', '百度地图:<a href=\"http://openapi.baidu.com/map/index.html\" target=\"_blank\">http://openapi.baidu.com/map/index.html</a><br />City8 &nbsp; &nbsp; :<a href=\"http://sh.city8.com/api.html\" target=\"_blank\">http://sh.city8.com/api.html</a>', '2011-01-06 15:41:30', '2011-05-26 14:39:39');
INSERT INTO `bb_core_blog` VALUES ('3', '1', 'PHPLinq', 'PHPLinq:<a href=\"http://phplinq.codeplex.com/\" target=\"_blank\">http://phplinq.codeplex.com/</a>', '2011-01-10 09:28:03', '2011-05-26 14:39:41');
INSERT INTO `bb_core_blog` VALUES ('4', '1', 'EditArea', 'EditArea:<a href=\"http://www.cdolivet.com/index.php?page=editArea\" target=\"_blank\">http://www.cdolivet.com/index.php?page=editArea</a>&nbsp;\r\n提供给开发者和工作者的用于编辑源码或者样式模板的TextArea', '2011-01-14 09:20:22', '2011-05-26 14:39:43');
INSERT INTO `bb_core_blog` VALUES ('5', '1', '名校公开课', '来自新浪、搜狐、网易和QQ的名校公开课。', '2011-05-12 16:56:59', '2011-05-26 14:39:46');

-- ----------------------------
-- Table structure for `bb_core_comment`
-- ----------------------------
DROP TABLE IF EXISTS `bb_core_comment`;
CREATE TABLE `bb_core_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '评论编号',
  `userId` int(11) NOT NULL COMMENT '评论者编号',
  `comment` longtext COMMENT '评论',
  `blogId` int(11) NOT NULL COMMENT '博客编号',
  `commitTime` timestamp NULL DEFAULT NULL COMMENT '提交时间',
  `updateTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`,`userId`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='评论';

-- ----------------------------
-- Records of bb_core_comment
-- ----------------------------
INSERT INTO `bb_core_comment` VALUES ('1', '1', 'TinyMCE：<a href=\"http://tinymce.moxiecode.com/\" target=\"_blank\">http://tinymce.moxiecode.com/</a>\r\n免费，开源，轻量的在线编辑器，基于 javascript，高度可定制，跨平台。', '1', '2011-01-06 12:09:43', '2011-05-26 14:39:54');
INSERT INTO `bb_core_comment` VALUES ('2', '1', 'FCKEditor：<a href=\"http://ckeditor.com/\" target=\"_blank\">http://ckeditor.com/</a>\r\n免费，开源，用户量庞大的在线编辑器，有良好的社区支持。', '1', '2011-01-06 12:09:47', '2011-05-26 14:39:58');
INSERT INTO `bb_core_comment` VALUES ('3', '1', 'YUI Editor：<a href=\"http://developer.yahoo.com/yui/editor/\" target=\"_blank\">http://developer.yahoo.com/yui/editor/</a>\r\n属于 Yahoo! YUI 的一部分，能输出纯净 Xhtml 代码。', '1', '2011-01-06 12:09:50', '2011-05-26 14:40:00');
INSERT INTO `bb_core_comment` VALUES ('4', '1', 'NicEdit:<a href=\"http://nicedit.com/\" target=\"_blank\">http://nicedit.com/</a>\r\n简单，易用，轻量，外观漂亮的在线编辑器。', '1', '2011-01-06 12:09:55', '2011-05-26 14:40:03');
INSERT INTO `bb_core_comment` VALUES ('5', '1', 'KindEditor:<a href=\"http://www.kindsoft.net/\" target=\"_blank\">http://www.kindsoft.net/</a>\r\nHTML编辑器在线编辑器可视化编辑器', '1', '2011-01-06 15:54:03', '2011-05-26 14:40:05');
INSERT INTO `bb_core_comment` VALUES ('6', '1', 'WebWiz RichTextEditor:<a href=\"http://www.webwiz.co.uk/webwizrichtexteditor/\" target=\"_blank\">http://www.webwiz.co.uk/webwizrichtexteditor/</a>\r\n这是一个商业产品，并不免费，但功能非常丰富，基于 ASP，JavaScript 和 DHTML。', '1', '2011-01-06 16:04:42', '2011-05-26 14:40:08');
INSERT INTO `bb_core_comment` VALUES ('7', '1', 'QQ淘课：<a target=\"_blank\" href=\"http://bb.news.qq.com/open.htm\">http://bb.news.qq.com/open.htm</a><br />', '5', '2011-05-12 16:06:07', '2011-05-26 14:40:11');
INSERT INTO `bb_core_comment` VALUES ('8', '1', '网易公开课：<a target=\"_blank\" href=\"http://v.163.com/open/\">http://v.163.com/open/</a><br />', '5', '2011-05-12 16:06:51', '2011-05-26 14:40:14');
INSERT INTO `bb_core_comment` VALUES ('9', '1', '搜狐名校公开课：<a target=\"_blank\" href=\"http://tv.sohu.com/open/\">http://tv.sohu.com/open/</a><br />', '5', '2011-05-12 16:07:22', '2011-05-26 14:40:17');
INSERT INTO `bb_core_comment` VALUES ('10', '1', '新浪名校公开课：<a target=\"_blank\" href=\"http://edu.sina.com.cn/video/open/index.shtml\">http://edu.sina.com.cn/video/open/index.shtml</a><br />', '5', '2011-05-12 16:08:54', '2011-05-26 14:40:20');
INSERT INTO `bb_core_comment` VALUES ('11', '1', '网络公开课：<a target=\"_blank\" href=\"http://www.cicistudy.com/index.php\">http://www.cicistudy.com/index.php</a><br />', '5', '2011-05-12 17:00:40', '2011-05-26 14:40:22');

-- ----------------------------
-- Table structure for `bb_log_log`
-- ----------------------------
DROP TABLE IF EXISTS `bb_log_log`;
CREATE TABLE `bb_log_log` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `logtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '日志记录时间',
  `ident` char(16) DEFAULT NULL COMMENT '标志或者分类',
  `priority` enum('0','1','2','3','4','5','6','7','8') DEFAULT '6' COMMENT '优先级\r\n0:严重错误\r\n1:警戒性错误\r\n2:临界值错误\r\n3:一般错误\r\n4:警告性错误\r\n5:通知\r\n6:信息\r\n7:调试\r\n8:SQL',
  `message` varchar(200) DEFAULT NULL COMMENT '日志内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='日志';

-- ----------------------------
-- Records of bb_log_log
-- ----------------------------

-- ----------------------------
-- Table structure for `bb_log_loguser`
-- ----------------------------
DROP TABLE IF EXISTS `bb_log_loguser`;
CREATE TABLE `bb_log_loguser` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `userId` int(11) NOT NULL COMMENT '用户编号',
  `type` enum('1','2','3') NOT NULL COMMENT '类型；枚举类型：\n1.吃饭\n2.干活\n3.睡觉',
  `content` varchar(200) DEFAULT NULL COMMENT '一般日志类型决定了内容；这一栏一般没有内容',
  `commitTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '提交更新时间',
  PRIMARY KEY (`id`,`userId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户日志';

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
  `state` enum('0','1') DEFAULT '0' COMMENT '枚举类型。\n0:未读\n1:已读',
  `commitTime` timestamp NULL DEFAULT NULL COMMENT '提交时间',
  `updateTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`senderId`,`receiverId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='消息';

-- ----------------------------
-- Records of bb_msg_msg
-- ----------------------------

-- ----------------------------
-- Table structure for `bb_msg_notice`
-- ----------------------------
DROP TABLE IF EXISTS `bb_msg_notice`;
CREATE TABLE `bb_msg_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `senderId` int(11) NOT NULL COMMENT '管理员编号',
  `group` varchar(200) DEFAULT NULL COMMENT '分类',
  `title` varchar(200) DEFAULT NULL COMMENT '标题',
  `content` varchar(1000) DEFAULT NULL COMMENT '通知内容',
  `commitTime` timestamp NULL DEFAULT NULL COMMENT '提交时间',
  `updateTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='通知';

-- ----------------------------
-- Records of bb_msg_notice
-- ----------------------------

-- ----------------------------
-- Table structure for `bb_msg_re_usernotice`
-- ----------------------------
DROP TABLE IF EXISTS `bb_msg_re_usernotice`;
CREATE TABLE `bb_msg_re_usernotice` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `userId` int(11) NOT NULL COMMENT '用户编号',
  `noticeId` int(11) NOT NULL COMMENT '通知编号',
  `commitTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`,`userId`,`noticeId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户收到通知关系表';

-- ----------------------------
-- Records of bb_msg_re_usernotice
-- ----------------------------

-- ----------------------------
-- Table structure for `bb_user_department`
-- ----------------------------
DROP TABLE IF EXISTS `bb_user_department`;
CREATE TABLE `bb_user_department` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `name` varchar(50) DEFAULT NULL COMMENT '部门名称',
  `manager` varchar(100) DEFAULT NULL COMMENT '管理者',
  `budget` int(11) DEFAULT NULL COMMENT '预算',
  `actualexpenses` int(11) DEFAULT NULL COMMENT '实际开销',
  `estsalary` int(11) DEFAULT NULL COMMENT '部门人员预估平均工资',
  `actualsalary` int(11) DEFAULT NULL COMMENT '部门人员实际平均工资',
  `commitTime` timestamp NULL DEFAULT NULL COMMENT '提交时间',
  `updateTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户所属部门';

-- ----------------------------
-- Records of bb_user_department
-- ----------------------------
INSERT INTO `bb_user_department` VALUES ('1', '项目部', 'skygreen', '1', '1', '1000000', '1000000', '2011-05-26 14:41:20', '2011-05-26 14:41:25');

-- ----------------------------
-- Table structure for `bb_user_function`
-- ----------------------------
DROP TABLE IF EXISTS `bb_user_function`;
CREATE TABLE `bb_user_function` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '权限编号',
  `url` varchar(500) DEFAULT NULL COMMENT '允许访问的URL权限',
  `commitTime` timestamp NULL DEFAULT NULL COMMENT '提交时间',
  `updateTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='功能信息';

-- ----------------------------
-- Records of bb_user_function
-- ----------------------------

-- ----------------------------
-- Table structure for `bb_user_re_rolefunction`
-- ----------------------------
DROP TABLE IF EXISTS `bb_user_re_rolefunction`;
CREATE TABLE `bb_user_re_rolefunction` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `roleId` int(11) NOT NULL COMMENT '角色编号',
  `functionId` int(11) NOT NULL COMMENT '功能编号',
  PRIMARY KEY (`id`,`roleId`,`functionId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='角色拥有功能关系表';

-- ----------------------------
-- Records of bb_user_re_rolefunction
-- ----------------------------

-- ----------------------------
-- Table structure for `bb_user_re_userrole`
-- ----------------------------
DROP TABLE IF EXISTS `bb_user_re_userrole`;
CREATE TABLE `bb_user_re_userrole` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `userId` int(11) NOT NULL COMMENT '用户编号',
  `roleId` int(11) NOT NULL COMMENT '角色编号',
  PRIMARY KEY (`id`,`roleId`,`userId`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户角色关系表';

-- ----------------------------
-- Records of bb_user_re_userrole
-- ----------------------------

-- ----------------------------
-- Table structure for `bb_user_role`
-- ----------------------------
DROP TABLE IF EXISTS `bb_user_role`;
CREATE TABLE `bb_user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '角色编号',
  `name` varchar(200) DEFAULT NULL COMMENT '角色名称',
  `commitTime` timestamp NULL DEFAULT NULL COMMENT '提交时间',
  `updateTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='角色';

-- ----------------------------
-- Records of bb_user_role
-- ----------------------------

-- ----------------------------
-- Table structure for `bb_user_user`
-- ----------------------------
DROP TABLE IF EXISTS `bb_user_user`;
CREATE TABLE `bb_user_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `departmentId` int(11) NOT NULL COMMENT '部门编号',
  `name` varchar(200) NOT NULL COMMENT '用户名',
  `password` varchar(200) DEFAULT NULL COMMENT '用户密码',
  `commitTime` timestamp NULL DEFAULT NULL COMMENT '提交时间',
  `updateTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`,`departmentId`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户';

-- ----------------------------
-- Records of bb_user_user
-- ----------------------------

INSERT INTO `bb_user_user` VALUES ('1', '0', 'admin', '21232f297a57a5a743894a0e4a801fc3', '2011-08-07 09:32:14', '2011-08-07 09:32:51');

-- ----------------------------
-- Table structure for `bb_user_userdetail`
-- ----------------------------
DROP TABLE IF EXISTS `bb_user_userdetail`;
CREATE TABLE `bb_user_userdetail` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `userId` int(11) NOT NULL COMMENT '用户编号',
  `email` varchar(500) DEFAULT NULL COMMENT '邮件地址',
  `cellphone` varchar(500) CHARACTER SET latin1 DEFAULT NULL COMMENT '手机号码',
  `commitTime` timestamp NULL DEFAULT NULL COMMENT '提交时间',
  `updateTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`,`userId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户详细信息';

-- ----------------------------
-- Records of bb_user_userdetail
-- ----------------------------
