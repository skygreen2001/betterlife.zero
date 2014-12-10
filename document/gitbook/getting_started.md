# 新手上路

##准备工作
1. 安装Wamp|Lamp|Mamp
Wamp安装说明:
Lamp安装说明:
Mamp安装说明:

2. 安装Git
Git安装说明

3. 安装示例数据库

    如果是Wamp,一般自带了Phpmyadmin，也可以安装Mysql数据库工具客户端如MysqlWorkbench或者Navicat等

    如果是Lamp或者Mamp需要另行安装Phpmyadmin

    示例数据库放置在根路径下:db/mysql 文件名称：db_betterlife.sql;它是本框架示例数据库mysql sql脚本备份。

    示例数据库的具体定义说明可参考本书[2.2.框架数据库示例]

##一步上手
只需要一步,就可以使用这个框架带来的好处了
* 引用init.php文件(如果在根路径下)
  示例:如果在根路径下引用:require_once("init.php");

##开始使用
现在可以使用这个框架了,如果习惯了sql的写法，可以通过直接使用函数:**sqlExecute**

例如:希望查看所有的博客记录
传统的sql语句:select * from bb_core_blog

完整的示例代码如下:

```
<?php
require_once("init.php");
$blogs=sqlExecute("select * from bb_core_blog");
print_r($blogs);
?>```

输出打印显示如下:
```
Array
(
    [0] => Array
        (
            [blog_id] => 1
            [user_id] => 1
            [blog_name] => Web在线编辑器
            [blog_content] => 搜索关键字：在线编辑器
引自：<a href="http://paranimage.com/22-online-web-editor/" target="_blank">http://paranimage.com/22-online-web-editor/</a>
            [commitTime] => 1331953386
            [updateTime] => 2013-12-26 15:27:05
        )
    ......

)
```



##面向对象


参考 [3.数据对象通用方法]



输出打印显示如下:
```

Array
(
    [0] => Blog Object
        (
            [blog_id] => 1
            [user_id] => 1
            [blog_name] => Web在线编辑器
            [blog_content] => 搜索关键字：在线编辑器
引自：<a href="http://paranimage.com/22-online-web-editor/" target="_blank">http://paranimage.com/22-online-web-editor/</a>
            [id:protected] =>
            [commitTime] => 1331953386
            [updateTime] => 2013-12-26 15:27:05
        )
    .....

)```









