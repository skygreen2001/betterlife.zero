# 数据对象通用方法

##定义通用方法列表
定义通用方法分为两类:实例方法和类方法。

### 实例方法【需实例化数据对象】
一般来讲数据对象的增删改定义为实例方法

* save:保存数据对象
* update:更新数据对象
* delete:删除数据对象

### 类方法【静态方法】
一般来讲数据对象的查询定义为类方法

* updateProperties:更新对象指定的属性
* updateBy:根据条件更新数据对象指定的属性
* deleteByID:由标识删除指定ID数据对象
* deleteByIds:根据主键删除多条记录
* deleteBy:根据条件删除多条记录
* increment:对属性进行递增
* decrement:对属性进行递减
* existByID:由标识判断指定ID数据对象是否存在
* existBy:判断符合条件的数据对象是否存在
* select:查询当前对象需显示属性的列表
* select_one:查询当前对象单个需显示的属性
* get:查询数据对象列表
* get_one:查询得到单个对象实体
* get_by_id:根据表ID主键获取指定的对象[ID对应的表列]
* count:数据对象总计数
* queryPage:数据对象分页
* queryPageByPageNo:数据对象分页根据当前页数和每页显示记录数

其他类方法
* toXml:数据对象转换成xml字符串
* toJson:数据对象转换成Json字符串
* toArray:数据对象转换成数组
* max:获取数据对象指定属性[表列]最大值
* min:获取数据对象指定属性[表列]最�小值
* sum:获取数据对象指定属性[表列]总和
* countMultitable:对象总计数[多表关联查询]
* queryPageMultitable:对象分页[多表关联查询]

##使用方法示例

### 调用实例方法

以实例方法:save 为例
```
$joe=new User();
$joe->setUsername("joy");
//$joe["username"]="wb";//也可以使用php的数组方式进行赋值
$joe->setPassword("tttt");
$joe->save($joe);```

### 调用类方法

以类方法: queryPage为例
```
$blogs=Blog::queryPage(0,10,
	array(
		//"(blog_content like '%关键字%' or blog_content like '%公开课%')",
		"blog_id<4",
		"user_id"=>1
	)
);
print_r($blogs);```

以类方法: select为例
```
$blogs=Blog::select('blog_name,blog_content');
print_r($blogs);```
