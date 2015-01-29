# 框架数据对象使用示例

##初始化

* 引用init.php文件(如果在根路径下)
  示例:如果在根路径下引用:require_once("init.php");

可以使用根路径下的test.php测试验证框架的通用方法。

### 实例方法示例
* save:保存数据对象

```
说明:保存用户信息
代码如下:
    $user=new User();
    $user->setUsername("betterlife");
    $user->setPassword("123456");
    $user->save();
    if($user_id) echo("新增用户标识:".$user_id); else echo("新增用户失败!");
输出显示如下:
    新增用户标识:3
```

* saveOrUpdate:保存或修改数据对象

```
说明:保存或更新用户信息
代码如下:
$user=User::get_by_id(3);
$user["username"]="shanghai";
$user_id=$user->saveOrUpdate();
if($user_id) echo("修改用户成功!"); else echo("修改用户失败!");
输出显示如下:
    新增用户标识:3
```


* update:更新数据对象

```
说明:修改用户信息
代码如下:
$user=User::get_by_id(3);
$user["username"]="shanghai";
$user->update();
if($user) echo("修改用户成功!"); else echo("修改用户失败!");
输出显示如下:
    新增用户标识:3
```

* delete:删除数据对象



### 类方法示例
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


* get_by_id:根据表ID主键获取指定的对象



* count:数据对象总计数

```
说明:查看博客的数量
代码如下:
    $countBlogs=Blog::count();
    echo($countBlogs);
```

```
输出显示如下:
    5
```

* queryPage:数据对象分页

```
说明:查看从第一到第5条博客记录
代码如下:
    $blogs=Blog::queryPage(0,10);
    print_r($blogs);
输出显示如下:
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

* queryPageByPageNo:数据对象分页根据当前页数和每页显示记录数

```
说明:查看第一页的博客记录，每页3条记录，无查询条件
代码如下:
    $blogs=Blog::queryPageByPageNo(1,null,3);
    print_r($blogs);
输出显示如下:
    Array
    (
        [count] => 5
        [pageCount] => 2
        [data] => Array
            (
                [0] => Blog Object
                    (
                        [blog_id] => 5
                        [user_id] => 1
                        [blog_name] => 名校公开课
                        [blog_content] => 来自新浪、搜狐、网易和QQ的名校公开课。
                        [id:protected] =>
                        [commitTime] => 1331953386
                        [updateTime] => 2013-12-26 15:27:05
                    )
                ......
            )
    )
```

### 数据对象关系调用示例
数据对象关系定义详细说明请参考[3.3.数据对象规格说明]相关章节说明。

* 一对一[has_one]

```
说明:查看用户详情
定义示例:
    class Userdetail extends DataObject {
    	static $belongs_has_one=array(
    		"user"=>"User"
    	);
代码如下:
    $user=User::get_by_id(1);
    $userdetail=$user->userdetail;
    print_r($userdetail);
输出显示如下:
    Userdetail Object
    (
        [userdetail_id] => 1
        [user_id] => 1
        [realname] => 周月璞
        [profile] => userdetail/profile/20140211094832.png
        [country] => 1
        [province] => 25
        [city] => 321
        [district] => 2709
        [address] => 上海市石岚三村80号404室
        [qq] => 412731900
        [sex] => 1
        [birthday] => 1979-03-10
        [id:protected] =>
        [commitTime] => 1331953386
        [updateTime] => 2014-01-09 19:39:52
    )
```

* 一对多[has_many]

```
说明:查看博客评论
定义示例:
    class Blog extends DataObject {
    	static $has_many=array(
    		"comments"=> "Comment",
    	);

代码如下:
    $blog=Blog::get_by_id(1);
    $comments=$blog->comments;
    print_r($comments);

输出显示如下:
Array
(
    [0] => Comment Object
        (
            [comment_id] => 6
            [user_id] => 2
            [comment] => WebWiz RichTextEditor:<a href="http://www.webwiz.co.uk/webwizrichtexteditor/" target="_blank">http://www.webwiz.co.uk/webwizrichtexteditor/</a>
这是一个商业产品，并不免费，但功能非常丰富，基于 ASP，JavaScript 和 DHTML。
            [blog_id] => 1
            [id:protected] =>
            [commitTime] => 1331953386
            [updateTime] => 2012-03-17 12:37:46
        )
    ......
)
```

* 多对多[many_many]

```
说明:查看用户角色
定义示例:

    class User extends DataObject {
    	static $many_many=array(
    		"roles"=> "Role",
    	);
代码如下:

    $user=User::get_by_id(1);
    $roles=$user->roles;
    print_r($roles);

输出显示如下:
Array
(
    [0] => Role Object
        (
            [role_id] => 1
            [role_name] => 项目经理
            [id:protected] =>
            [commitTime] => 1331953386
            [updateTime] => 2015-01-17 20:51:45
        )

    ......
)
```

* 从属于一对一[belong_has_one]

```
说明:获取用户信息
定义示例:
    class Userdetail extends DataObject {
    	static $belongs_has_one=array(
    		"user"=>"User"
    	);
代码如下:
    $userdetail=Userdetail::get_by_id(1);
    $user=$userdetail->user;
    print_r($user);
输出显示如下:
    User Object
    (
        [user_id] => 1
        [username] => admin
        [password] => 21232f297a57a5a743894a0e4a801fc3
        [email] => skygreen2001@gmail.com
        [cellphone] => 13917320293
        [loginTimes] => 0
        [id:protected] =>
        [commitTime] => 1331953415
        [updateTime] => 2013-12-26 14:31:27
    )
```

* 从属于多对多[belongs_many_many]

```
说明:查看角色用户
定义示例:

    class Role extends DataObject {
    	static $belongs_many_many=array(
    		"users"=>"User"
    	);

代码如下:

    $role=Role::get_by_id(1);
    $users=$role->users;
    print_r($users);

输出显示如下:
Array
(
    [0] => User Object
        (
            [user_id] => 1
            [username] => admin
            [password] => 21232f297a57a5a743894a0e4a801fc3
            [email] => skygreen2001@gmail.com
            [cellphone] => 13917320293
            [loginTimes] => 0
            [id:protected] =>
            [commitTime] => 1331953415
            [updateTime] => 2013-12-26 14:31:27
        )

)

```

### 其它实例方法示例

* toXml:数据对象转换成xml字符串


* toJson:数据对象转换成Json字符串


* toArray:数据对象转换成数组


* saveRelationForManyToMany[数据对象多对多存储]







### 其它类方法示例


* max:获取数据对象指定属性[表列]最大值



* min:获取数据对象指定属性[表列]最小值



* sum:获取数据对象指定属性[表列]总和



* countMultitable:对象总计数[多表关联查询]



* queryPageMultitable:对象分页[多表关联查询]





