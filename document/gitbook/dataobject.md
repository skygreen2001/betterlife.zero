# 数据对象通用方法

路径    :core/model
文件名称：DataObject.php
github路径:https://github.com/skygreen2001/betterlife/blob/master/core/model/DataObject.php

每个数据对象都继承它，可以使用以下方法直接执行数据库的操作

定义通用方法说明如下
实例方法【需实例化数据对象】
* save
* update
* delete

类方法  【静态方法】
* updateProperties
* updateBy
* deleteByID
* deleteByIds
* deleteBy
* increment
* decrement
* existByID
* existBy
* select
* select_one
* get
* get_one
* get_by_id
* count
* queryPage
* queryPageByPageNo

其他类方法
* toXml
* toJson
* toArray
* max
* min
* sum
* countMultitable
* queryPageMultitable

