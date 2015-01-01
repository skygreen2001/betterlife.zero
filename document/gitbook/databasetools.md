# 数据库小工具

在框架里内嵌了一些数据库小工具
```
它小,够轻,实用！```

数据库工具汇总地址:http://127.0.0.1/betterlife/tools/tools/db.php

数据库小工具分为两类:
1. 数据库常用脚本生成工具

    说明:只是生成sql脚本，并非直接运行生效，它需要手动在mysql执行脚本方能生效

2. 数据库代码生成

## 数据库常用脚本生成工具
### 修改数据库表前缀名
访问地址:http://127.0.0.1/betterlife/tools/tools/db/rename_db_prefix.php

输入参数

    原前缀名:数据库表原前缀名称,如bb_或者bb

    新前缀名:数据库表新前缀名称,如bc_或者bc


### 替换所有表里的关键词
访问地址:http://127.0.0.1/betterlife/tools/tools/db/db_replace_keywords.php

输入参数

    原关键字:数据库表中内容原关键字,如原网站名称

    新关键字:数据库表中内容原关键字,如新网站名称

### 删除所有的表数据
http://127.0.0.1/betterlife/tools/tools/db/db_delete_data.php

### 删除所有的表
http://127.0.0.1/betterlife/tools/tools/db/db_delete_tables.php

### 移植数据库表从Mysql到Sqlserver
参见本框架的兄弟地址:Betterlife.Net
背景:国内开发另一支主力-asp.net开发,它的数据库定义规范和习惯与php阵营略有不同，这个工具主要就是将符合betterlife框架数据库定义的表转换成sqlserver定义的表。

说明:本框架另有分支net,它会生成asp.net使用的extjs框架,可以零代价切换到asp.net后台开发

http://127.0.0.1/betterlife/tools/tools/db/sqlserver/db_sqlserver_convert_prepare.php

## 数据库代码生成
### 一键生成用于Web应用开发的初始模型
http://127.0.0.1/betterlife/tools/tools/autocode/db_onekey.php

### 数据库生成实体类
http://127.0.0.1/betterlife/tools/tools/autocode/layer/domain/db_domain.php

### 数据库生成Java实体类
http://127.0.0.1/betterlife/tools/tools/autocode/layer/domain/db_domain_java.php

### 数据库生成服务类
http://127.0.0.1/betterlife/tools/tools/autocode/layer/db_service.php

### 数据库生成控制器类
http://127.0.0.1/betterlife/tools/tools/autocode/layer/db_action.php

### 数据库生成Extjs表示层
http://127.0.0.1/betterlife/tools/tools/autocode/layer/view/db_view_ext.php

### 数据库生成默认通用的表示层
http://127.0.0.1/betterlife/tools/tools/autocode/layer/view/db_view_default.php
