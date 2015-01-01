# 各种类型的数据库
##概述
强烈推荐使用apache+php+mysql经典组合，如非特殊的原因，这是最佳的组合;正如同java和oracle数据库、asp.net和sqlserver数据库是黄金组合;尽量遵守业内默认的潜规则，这样在开发中可以在获得最大程度的技术资料支持

如果作为开发者的您是新手,那么以上规则最适合您;它会让您少走弯路,了解并掌握开发主流针对具体问题的解决提出的从战略到战术层面的各种具体的解决方案。

然后在现实的场景中，什么都有可能发生，事实上据我了解，国内仍有很多人喜欢使用sqlserver,同时在php阵营中也常使用sqlite活着PostgresSql;同时作为大型数据库的主流:Oracle、Informix和DB2,也有使用的情况存在。

本框架参考Hibernate的做法,完全支持多个数据库,并且因为使用php语言的原因，使用它很简单:

在数据库配置文件Config_Db.php里配置:
1. $db:数据库方式类别|数据源定义

默认使用Mysql数据库

可以使用的数据库类型值参考定义在同一个文件里的枚举类型:EnumDbSource

可以使用的数据库列举如下:
* Mysql
* PostgresSql;需要 PostgreSQL 8.2 and later
* Oracle
* Informix
* IBM Db2
* Microsoft Excel;ODBC支持
* Microsoft Access
* Microsoft Sql Server
* Microsoft Sybase
* FreeTDS
* FireBird
* Interbase
* LDAP
* Sqlite 2
* Sqlite 3
* PHP自带的SQLite,数据存储在内存中

2. $engine:数据库使用调用引擎

默认使用Mysql的Mysqli方式

可以使用的数据库使用调用引擎参考定义在同一个文件里的枚举类型:EnumDbEngine

数据库使用调用引擎分为两种:
* 面向对象的方式

 基本上每种数据库会提供一种引擎

 其中默认mysql使用的mysqli方式,还有php自带默认调用mysql的方式

* DAL 方式

这种方式主要整合第三方Dal开源的框架，它们已经比较成熟，经得起考验

最常使用的时Adodb、Pdo和Mdb2方式,其他还有ActiveRecord、Dbfacile、Propel都是很不错的选择;本框架针对前3种做了完整的实现,其它几种Dal方式也提供了容器,具体实现可参考前三种方式实现。

##各种数据库脚本

本框架中对国内项目经常使用到的几种数据库做了具体的实践验证并提供有示例,它们都在根路径db目录下,它们分别是:
* Mysql
* Sqlserver
* Sqlite
* PostgresSql
* Access

##进一步配置
各种数据库都各有特色擅长之处，对于它们的个性具体体现在配置文件中，它们可以在框架根路径目录:config/config/db下。
