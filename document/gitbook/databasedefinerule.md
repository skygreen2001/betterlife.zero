# 数据库原型设计规范

推荐使用数据库设计IDE工具MysqlWorkBench进行表设计,通过MysqlWorkBench的Forward Engineer工具直接生成数据库Sql脚本或者直接生成数据库。

也可以直接用Phpmyadmin或者Navicat直接进行数据库表定义。

请注意以下规范中提到的表注释和列注释要求,它直接关系后面代码生成和数据库说明书的生成，严格按规范定义后，即可做到一处定义,代码、文档都可自动实时生成相应的注释，无需额外的工作。

* 以下规范中定义的"_"是半角，不是全角。
* 表名定义:库名缩写+“_”+目录名+"_"+类名[头字母小写]
  如乐活的博客表：
      库名缩写：bb
      目录名：core
      类名：Blog
  所以表名:bb_core_blog
  表名最后一段不能命名为特殊关键字如new：如bb_core_new

* 每张表需要定义表注释
* 每张表每列需定义列注释
* 每张表需定义字段：
  标识    :类名[头字母小写]+"_"+"id"
  创建时间:commitTime
  更新时间:updateTime

* 标识列名定义为：类名[头字母小写]+“_”+"id"
  如乐活的博客表：blog_id

* 表与表之间存在关系的主键与原表中的主键定义一致
  1. 用户表和用户详情表存在一对一关系,在用户详情中的关系主键user_id和用户表的主键是一致的。
  2. 用户表和角色表存在多对多关系，在中间关系表:bb_user_re_userrole中关系主键user_id和用户表的主键是一致的,role_id和角色表的主键是一致的。
  3. 用户表和博客表存在一对多关系，在博客表:bb_core_blog中关系主键user_id和用户表的主键是一致的。

* 标识列为自增长，AI列勾选。

* 列类型为int时，默认值为0

* 列名不能为name，最好加前缀，如博客表就为blog_name。

* 枚举类型注释定义:
  枚举值:枚举值说明-枚举值英文名(或拼音名)
  每句之间以回车或者;分割
  如用户详情表中的性别定义为：sexType
  类型:enum('0','1','2')
  性别
    0:女-female
    1:男-male
    2:待确认-unknown

* updateTime默认值是CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

* 图片列定义规则：
    列名称为：image|img|ico|logo|pic

* 需TextArea输入大文本列定义规则:
    1. 列名称为：intro|memo
    2. 列类型为：text|longtext
    3. 特殊情况：列长度>=500并且名称不能为images|link|ico

* 邮箱列定义规则:
  1. 列名称含有:email
  2. 列名称注释含有:邮件,邮箱
  3. 并且列名称不含有is【如是否发送邮件列就不是邮箱列】

* 密码列定义规则:
  表名称含有:member|user|admin 并且列名称含有：password

* 当和其他表是一对一的关系:
    需设置外键字段为Unique
    如用户和用户详情表，则需要在用户详情表里指明外键user_id是Unique。

* 多对多关系表名称中间用"_re_"连接。如：bb_user_re_userrole
