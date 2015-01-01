# 新手上路

##一. 准备工作

###1.安装环境

Wamp安装说明:

Lamp安装说明:

Mamp安装说明:

###2.安装Git


* 预先安装Git，如果是Linux Ubuntu系统，执行指令如下:

    1.安装git: apt-get install git

    2.生成ssh公钥和私钥，并将公钥提供给git代码库或管理者
        ssh-keygen -t rsa -C "skygreen2001@gmail.com"
        参考:https://help.github.com/articles/generating-ssh-keys

    3.下载web应用如:git@github.com:skygreen2001/betterlife.git

* 安装Git客户端工具:

    1.sourcetree:

    2.tortoiseGit:


###3. 安装示例数据库

* 如果是Wamp,一般自带了Phpmyadmin，也可以安装Mysql数据库工具客户端如MysqlWorkbench或者Navicat等

* 如果是Lamp或者Mamp需要另行安装Phpmyadmin

* 示例数据库放置在根路径下:db/mysql

  文件名称：db_betterlife.sql;它是本框架示例数据库mysql sql脚本备份。

* 示例数据库的具体定义说明可参考本书[2.2.框架数据库示例]

###4. 安装须知

安装本框架需要执行的一些操作要求可通过运行地址:http://127.0.0.1/betterlife/install/

###5. 安装开发工具:Sublime

##二. 一步上手
在一个php文件里只需要一步,就可以使用这个框架带来的好处了
* 引用init.php文件(如果在根路径下)
  示例:如果在根路径下引用:require_once("init.php");

##三. 开始使用
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

##四.面向对象

参考 [3.数据对象通用方法]

以类方法:分页查询queryPage为例
```
$blogs=Blog::queryPage(0,10);
print_r($blogs);```

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

##五.工程重用

前面我们掌握了这个框架最基础的概念,接下来我们关注的是怎样根据自己项目的需要,快速搭建一个项目的框架;

工程重用可通过访问框架本地首页地址:
http://127.0.0.1/betterlife/

下方应有以下文字链接:工程重用|数据库说明书|一键生成|帮助;
点击其中的文字链接:工程重用

工程重用链接地址:http://127.0.0.1/betterlife/tools/dev/index.php

根据自己项目的需求修改相关项目配置:
* Web项目名称【中文】
* Web项目名称【英文】
* Web项目别名
* 输出Web项目路径
* 数据库名称
* 数据库表名前缀
* 帮助地址
* 重用类型

假设我们需要创建一个新的项目:bettercity

它的定义如下:
* Web项目名称【中文】:美好的城市-上海
* Web项目名称【英文】:bettercity
* Web项目别名        :Bc
* 输出Web项目路径    :bettercity
* 数据库名称         :bettercity
* 数据库表名前缀     :bc_
* 帮助地址           :默认的值,不变
* 重用类型           :通用版

##六.代码生成

在新生成的项目里:bettercity

* 如果新项目的业务逻辑和主流程大致相同,那么可以考虑重用现有的数据库，使用［2.4数据库定义的小工具］里的工具[修改数据库表前缀名]


    访问地址:http://127.0.0.1/bettercity/tools/tools/db/rename_db_prefix.php

* 如果新项目的业务逻辑和原项目的主流程不同,可以按照[2.数据库原型设计规范]定义数据库

在完成了新项目的数据库设计之后,就可以使用代码生成工具生成新项目的通用代码。

代码生成可通过访问框架本地首页地址:
http://127.0.0.1/bettercity/

下方应有以下文字链接:工程重用|数据库说明书|一键生成|帮助;
点击其中的文字链接:一键生成

一键生成链接地址:http://127.0.0.1/bettercity/tools/tools/autocode/db_onekey.php










