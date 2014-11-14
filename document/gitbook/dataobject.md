# 数据对象通用方法

## 定位
路径    :core/model/

文件名称：DataObject.php

github路径:https://github.com/skygreen2001/betterlife/blob/master/core/model/DataObject.php

每个数据对象都继承它，可以使用以下方法直接执行数据库的操作

##概述

数据库里的每张表对应一个数据对象
表名定义规则:库名缩写+“_”+目录名+"_"+类名[头字母小写]
如博客表名定义为bb_core_blog
那么博客数据对象即:Blog[头字母大些]
数据对象可以直接使用以下定义的通用方法快速进行数据库的操作

##定义通用方法列表
定义通用方法说明如下
实例方法【需实例化数据对象】
* save:保存数据对象
* update:更新数据对象
* delete:删除数据对象

类方法  【静态方法】
* updateProperties:
* updateBy:
* deleteByID:
* deleteByIds:
* deleteBy:
* increment:
* decrement:
* existByID:
* existBy:
* select:
* select_one:
* get:
* get_one:
* get_by_id:
* count:数据对象总计数
* queryPage:数据对象分页
* queryPageByPageNo:数据对象分页根据当前页数和每页显示记录数

其他类方法
* toXml:数据对象转换成xml字符串
* toJson:数据对象转换成Json字符串
* toArray:数据对象转换成数组
* max:获取数据对象指定属性[表列]最大值
* min:获取数据对象指定属性[表列]最���小值
* sum:获取数据对象指定属性[表列]总和
* countMultitable:对象总计数[多表关联查询]
* queryPageMultitable:对象分页[多表关联查询]

