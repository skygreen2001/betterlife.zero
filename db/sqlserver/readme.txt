1.Sqlserver 的数据库可新建数据库名为betterlife然后通过Microsoft Access数据库导入表创建
2.sqlncli.msi为微软提供的PHP访问Sql Server数据库DSN Less方式【即无需再window的控制面板-》管理工具-》数据源(ODBC)中设置DSN】时需要安装的驱动；
  当Config_Db文件里设置为配置$is_dsn_set=false;时需要安装方能使访问程序有效；
  否则会抛出异常：[Microsoft][ODBC 驱动程序管理器] 未发现数据源名称并且未指定默认驱动程序

3.可以导入Microsoft Access里的测试数据库Betterlife，导入后需要在Sql Server数据库里设置每张表的id字段为自增长标识字段