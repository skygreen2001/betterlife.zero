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
    $user_id=$user->save();
    if($user_id) echo("新增用户标识:".$user_id); else echo("新增用户失败!");
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
    if($user) echo("修改用户信息成功!"); else echo("修改用户信息失败!");
输出显示如下:
    修改用户信息成功!
```

* saveOrUpdate:保存或修改数据对象

```
说明:保存或更新用户信息
代码如下:
    $user=User::get_by_id(3);
    $user["username"]="shanghai";
    $user_id=$user->saveOrUpdate();
    if($user_id>1) echo("新增用户标识:".$user_id); elseif($user_id==true) echo("修改用户信息成功!");else  echo("修改用户信息失败!");
输出显示如下:
    修改用户信息成功!
```

* delete:删除数据对象

```
说明:删除用户信息
代码如下:
    $user=User::get_by_id(3);
    $isDelete=$user->delete();
    if($isDelete) echo("删除用户信息成功!"); else echo("删除用户信息失败!");
输出显示如下:
    删除用户信息成功!
```

### 类方法示例
* updateProperties:更新对象指定的属性

```
说明:修改用户指定的信息
代码如下:
    $isUpdate=User::updateProperties("1,2","loginTimes=100");
    if($isUpdate) echo("修改用户信息成功!"); else echo("修改用户信息失败!");
输出显示如下:
    修改用户信息成功!
```

* updateBy:根据条件更新数据对象指定的属性

```
说明:修改用户指定的信息
代码如下:
    $isUpdate=User::updateBy("username='admin'","loginTimes=500");
    if($isUpdate) echo("修改用户信息成功!"); else echo("修改用户信息失败!");
输出显示如下:
    修改用户信息成功!
```

* deleteByID:由标识删除指定ID数据对象

```
说明:删除指定标识的用户
代码如下:
    $isDelete=User::deleteByID(4);
    if($isDelete) echo("删除用户信息成功!"); else echo("删除用户信息失败!");
输出显示如下:
    删除用户信息成功!
```

* deleteByIds:根据主键删除多条记录

```
说明:删除多条指定标识的用户
代码如下:
    $isDelete=User::deleteByIds("5,6,7");
    if($isDelete) echo("删除用户信息成功!"); else echo("删除用户信息失败!");
输出显示如下:
    删除用户信息成功!
```

* deleteBy:根据条件删除多条记录

```
说明:删除指定条件的用户
代码如下:
    $isDelete=User::deleteBy("username='betterlife7'");
    if($isDelete) echo("删除用户信息成功!"); else echo("删除用户信息失败!");
输出显示如下:
    删除用户信息成功!
```

* increment:对属性进行递增

```
说明:用户访问次数+5
代码如下:
    $isPlus=User::increment("user_id>1","loginTimes",5);
    if($isPlus) echo("用户访问次数+5成功!"); else echo("用户访问次数+5失败!");
输出显示如下:
    用户访问次数+5成功!
```

* decrement:对属性进行递减

```
说明:用户访问次数-3
代码如下:
    $isMinus=User::decrement("user_id>1","loginTimes",3);
    if($isMinus) echo("用户访问次数-3成功!"); else echo("用户访问次数-3失败!");
输出显示如下:
    用户访问次数-3成功!
```

* existByID:由标识判断指定ID数据对象是否存在

```
说明:查看标识为1的用户是否存在
代码如下:
    $isExist=User::existByID(1);
    if($isExist) echo("指定标识的用户存在!"); else echo("指定标识的用户不存在!");
输出显示如下:
    指定标识的用户存在!"
```

* existBy:判断符合条件的数据对象是否存在

```
说明:查看用户名为china的用户是否存在
代码如下:
    $isExist=User::existBy("username='china'");
    if($isExist) echo("用户名为china的用户存在!"); else echo("用户名为china的用户不存在!");
输出显示如下:
    指定标识的用户存在!"
```

* select:查询当前对象需显示属性的列表

```
说明:查看博客名称列表
代码如下:
    $blog_names=Blog::select("blog_name");
    print_r($blog_names);
输出显示如下:
    Array
    (
        [0] => 名校公开课
        [1] => EditArea
        [2] => PHPLinq
        [3] => 地图导航第三方库
        [4] => Web在线编辑器
    )
```

* select_one:查询当前对象单个需显示的属性

```
说明:查看一个博客名称
代码如下:
    $blog_name=Blog::select_one("blog_name");
    print_r($blog_name);
输出显示如下:
    名校公开课
```

* get:查询数据对象列表

```
说明:查看博客列表
代码如下:
    $blogs=Blog::get();
    print_r($blogs);
输出显示如下:
    Array
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
```

* get_one:查询得到单个对象实体

```
说明:查看一个博客
代码如下:
    $blog=Blog::get_one();
    print_r($blog);
输出显示如下:
    Blog Object
    (
        [blog_id] => 5
        [user_id] => 1
        [blog_name] => 名校公开课
        [blog_content] => 来自新浪、搜狐、网易和QQ的名校公开课。
        [id:protected] =>
        [commitTime] => 1331953386
        [updateTime] => 2013-12-26 15:27:05
    )
```

* get_by_id:根据表ID主键获取指定的对象

```
说明:查看指定标识的博客
代码如下:
    $blog=Blog::get_by_id(1);
    print_r($blog);
输出显示如下:
    Blog Object
    (
        [blog_id] => 1
        [user_id] => 1
        [blog_name] => Web在线编辑器
        [blog_content] => 搜索关键字：在线编辑器...
        [id:protected] =>
        [commitTime] => 1331953386
        [updateTime] => 2013-12-26 15:27:05
    )
```

* count:数据对象总计数

```
说明:查看博客的数量
代码如下:
    $countBlogs=Blog::count();
    echo($countBlogs);
输出显示如下:
    5
```

* queryPage:数据对象分页

```
说明:查看从第1到5条博客记录
代码如下:
    $blogs=Blog::queryPage(1,5);
    print_r($blogs);
输出显示如下:
    Array
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
        .....

    )
```

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
        ......
    )
```

### 其它实例方法示例
* toXml:数据对象转换成xml字符串

```
说明:博客转换成xml字符串
代码如下:
    $blog=Blog::get_one();
    echo($blog->toXml());
输出显示如下:
    <?xml version="1.0" encoding="utf-8"?>
    <Blog>
        <blog_id>5</blog_id>
        <user_id>1</user_id>
        <blog_name>名校公开课</blog_name>
        <blog_content>来自新浪、搜狐、网易和QQ的名校公开课。</blog_content>
        <commitTime>1331953386</commitTime>
        <updateTime>2013-12-26 15:27:05</updateTime>
    </Blog>
```

* toJson:数据对象转换成Json字符串

```
说明:博客转换成Json字符串
代码如下:
    $blog=Blog::get_one();
    echo($blog->toJson());
输出显示如下:
    {"blog_id":5,"user_id":1,"blog_name":"\u540d\u6821\u516c\u5f00\u8bfe","blog_content":"\u6765\u81ea\u65b0\u6d6a\u3001\u641c\u72d0\u3001\u7f51\u6613\u548cQQ\u7684\u540d\u6821\u516c\u5f00\u8bfe\u3002","commitTime":1331953386,"updateTime":"2013-12-26 15:27:05"}
```

* toArray:数据对象转换成数组

```
说明:博客转换成数组
代码如下:
    $blog=Blog::get_one();
    print_r($blog->toArray());
输出显示如下:
    Array
    (
        [blog_id] => 5
        [user_id] => 1
        [blog_name] => 名校公开课
        [blog_content] => 来自新浪、搜狐、网易和QQ的名校公开课。
        [id] =>
        [commitTime] => 1331953386
        [updateTime] => 2013-12-26 15:27:05
    )
```

* saveRelationForManyToMany[数据对象多对多存储]

```
说明:存储角色用户多对多关系
代码如下:
    $role=new Role();
    $role->role_id=5;
    $role->role_name="高级程序员";
    UtilDateTime::ChinaTime();
    $role->save();
    $role->saveRelationForManyToMany("users","1");
    print_r($role);
输出显示如下:
    Role Object
    (
        [role_id] => 5
        [role_name] => 高级程序员
        [commitTime] => 1422779198
        [updateTime] => 2015-02-01 16:26:38
    )
```

### 其它类方法示例
* max:获取数据对象指定属性[表列]最大值

```
说明:获取用户访问次数最高的次数
代码如下:
    $max=User::max("loginTimes");
    echo($max);
输出显示如下:
    500
```

* min:获取数据对象指定属性[表列]最小值

```
说明:获取用户访问次数最低的次数
代码如下:
    $min=User::min("loginTimes");
    echo($min);
输出显示如下:
    2
```

* sum:获取数据对象指定属性[表列]总和

```
说明:获取用户访问次数的总和
代码如下:
    $sum=User::sum("loginTimes");
    echo($sum);
输出显示如下:
    630
```

* countMultitable:对象总计数[多表关联查询]

```
说明:获取博客名称含有Web的评论数
代码如下:
    $count=Comment::countMultitable("Blog a,Comment b","b.blog_id=a.blog_id and a.blog_name like '%Web%'");
    echo($count);
输出显示如下:
    6
```

* queryPageMultitable:对象分页[多表关联查询]

```
说明:获取博客名称含有Web的评论
代码如下:
    $comments=Comment::queryPageMultitable(1,6,"Blog a,Comment b","b.blog_id=a.blog_id and a.blog_name like '%Web%'");
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
