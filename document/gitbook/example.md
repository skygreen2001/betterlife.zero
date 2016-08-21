# 框架数据库示例

##定位
路径    :db/mysql

* 文件名称：db_betterlife.sql

mysql数据库sql脚本备份

github路径:https://github.com/skygreen2001/betterlife/blob/master/db/mysql/db_betterlife.sql

* 文件名称：dbdesign_betterlife.mwb

使用数据库设计IDE工具MysqlWorkBench进行表设计,严格按照［数据库原型设计规范］定义

github路径:https://github.com/skygreen2001/betterlife/blob/master/db/mysql/dbdesign_betterlife.mwb

##概述
   可运行框架下自带的脚本文件:db_betterlife.sql
   框架以博客网站为原型创建了网站前台和后台。


##示例数据库表清单

| 表名称 | 用途 | 备注 |
| -- | -- | -- |
| bb_core_blog | 博客 |    博客|
| bb_core_comment | 评论 | 评论 |
| bb_dic_region | 地区 | 地区 |
| bb_log_logsystem | 系统日志 |    系统日志 |
| bb_log_loguser | 用户日志 | 用户日志 |
| bb_msg_msg | 消息 | 消息 |
| bb_msg_notice | 通知 | 通知 |
| bb_msg_re_usernotice  | 用户收到通知 | 用户收到通知<br/>用户收到通知关系表 |
| bb_user_admin | 系统管理人员 | 系统管理人员 |
| bb_user_department | 用户所属部门 | 用户所属部门 |
| bb_user_functions | 功能信息 | 功能信息 |
| bb_user_re_rolefunctions | 角色拥有功能 | 角色拥有功能<br/>角色拥有功能关系表 |
| bb_user_re_userrole | 用户角色 | 用户角色<br/>用户角色关系表 |
| bb_user_role | 角色 | 角色 |
| bb_user_user | 用户 | 用户 |
| bb_user_userdetail | 用户详细信息 | 用户详细信息 |

##更多详情
访问示例数据库的说明书可通过访问框架本地首页地址:
http://127.0.0.1/betterlife/

下方应有以下文字链接:工程重用|数据库说明书|一键生成|帮助;
点击其中的文字链接:数据库说明书

数据库说明书链接地址:http://127.0.0.1/betterlife/tools/tools/db/manual/db_normal.php

需要说明的是:该数据库说明书是实时的,当你新建一个项目，创建了不同的数据库，只要按照框架定义数据库，那么这个地址可以实时生成数据库说明书，而它真正做到了一处定义，全局使用。
