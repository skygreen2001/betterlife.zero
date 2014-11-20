# 执行SQL查询语句


##一步上手
只需要一步,就可以使用这个框架带来的好处了
* 引用init.php文件(根路径下)
  示例:如果在根路径下引用:require_once("init.php");

##开始使用
现在可以使用这个框架了,如果习惯了sql的写法，可以通过直接使用函数:**sqlExecute**

示例如下:
    查询所有的博客记录:
```
    $sqlstr="select * from bb_core_blog";
    sqlExecute($sqlstr);```


进一步了解:**sqlExecute**
* 定位

    路径    :include/

    文件名称：common.php

    github路径:https://github.com/skygreen2001/betterlife/blob/master/include/common.php

* 定义如下

```
/**
 * 直接执行SQL语句
 * @param mixed $sql SQL查询语句
 * @param string|class|bool $object 需要生成注入的对象实体|类名称
 * @return array 默认返回数组,如果$object指定数据对象，返回指定数据对象列表，$object=true，返回stdClass列表。
 */
function sqlExecute($sqlstring,$object=null)```


## 完整的示例代码

查询所有的博客数据[只需要三句]

```
require_once("init.php");
$sqlstr="select * from bb_core_blog";
sqlExecute($sqlstr);```

