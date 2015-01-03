# 数据对象规格说明

##概述
数据对象除了属性和方法的定义，还有更多高级定义方式，其实现
数据对象的关系定义主要放置在类DataObjectSpec里DataObjectRelation里

路径    :core/model/dataobject/

文件名称：DataObjectSpec.php

文件名称：DataObjectRelation.php

数据对象的规格说明默认定义变量如下:
1. $field_spec:列规格定义说明
2. $has_one:一对一关系说明
3. $belong_has_one:从属一对一关系说明
4. $has_many:一对多关系说明
5. $many_many:多对多关系说明
6. $belongs_many_many:从属于多对多关系说明

除第一个变量用于列规格说明外，其它变量都用于表[类]关系说明;

第一个变量为实例变量,其他关系变量都定义为静态类变量;

一般情况下数据对象无需定义规格说明,定义了规格说明以后，利用框架内置的规格说明实现功能，可以轻而易举实现很多功能，这在后面会进一步详细说明;

通过代码生成数据对象类说明的时候,会自动生成相应的数据对象的规格说明配置变量;

如果未按照[2.数据库原型设计规范]定义数据库，则需要按本文说明手动配置数据对象的规格说明配置变量。

##详细说明
###$field_spec:列规格定义说明

数据对象定义需定义字段：public $field_spec，它定义了当前数据对象的列规格说明。

定义形式如下:

    public $field_spec= array(
    	EnumColumnNameDefault::ID=>'id',
    	EnumColumnNameDefault::COMMITTIME=>'publishTime',
    	EnumDataSpec::REMOVE=>array(
    		'updateTime'
    	),
    	EnumDataSpec::MANY_MANY_TABLE=>array(
    		//多对多关系类名=>多对多关系表名
    	),
    	EnumDataSpec::FOREIGN_ID=>array(
    		//类名=>外键名
    		//实例对象名=>外键名[在从属于一对一关系类名中经常遇到，比如家庭地址中省|市|区都关联同一个类:地区；需要用实例对象名]
    	)
    );

####基本定义
在数据对象的列规格里

1.$key->$value说明是：DataObject默认列名->列别名。

它主要用于与第三方WEB应用整合时，可能数据对象表唯一标识定义为$table_id,如用户表的唯一标识是：user_id;

在框架中设计当列名有别名时，以列别名去表中查找相应列。

2.remove:在数据对象中移除不需要持久化的列。

如数据对象中不需要列commitTime或者updateTime数据列时，只需要在其中声明，其中声明的列即不在框架的持久层中进行存储。

3.many_many_table:多对多关系表名称定义，如无定义，则按默认规则查找指定表。

多对多表名默认规则：

    多对多【主控端-即定义为$many_many】:数据库表名前缀+“_”+[文件夹目录+“_”]...+TABLENAME_RELATION+"_"+主表名+关系表名。
    如User和Role是多对多关系，数据库表名前缀为bb,文件夹目录是user,TABLENAME_RELATION是re；那么在User里定义$many_many包含:Role;则对应的表名是:bb_user_re_userrole.
    多对多【从属端-即定义为$belongs_many_many】:数据库表名前缀+“_”+[文件夹目录+“_”]...+TABLENAME_RELATION+"_"+关系表名+主表名。
    如User和Role是多对多关系，数据库表名前缀为bb,文件夹目录是user,TABLENAME_RELATION是re；那么在Role里定义$belongs_many_many包含:User;则对应的表名是:bb_user_re_userrole.

4.foreign_id:在对象之间或者说表之间存在一对一，一对多，多对多的关系时，可通过它指定外键的名称，如果没有指定，则按默认定义。

外键的名称默认定义：

* 一对一:【关系表类名+"_"+id】；注意关系表类名头字母小写

    如UserDetail和User是一对一关系，则在UserDetail中对应User的外键就是：user_id。
    在User中定义$has_one是UserDetail，在UserDetail定义$belong_has_one是User

* 一对多:【关系表类名+"_"+id】；注意关系表类名头字母小写

    如Department和User是一对多关系，则在User中对应Department的外键就是：department_id
    在User中定义$belong_has_one是Department，在Department中定义$has_many是User。

* 多对多【主控端】:多对多关系会产生一张中间表,它定义在EnumDataSpec::MANY_MANY_TABLE里，

    注意表类名头字母小写。
    主表类外键名称：【主表类名+"_"+id】，关系表类外键名称：【关系表类名+"_"+id】

* 多对多【从属端】:多对多关系会产生一张中间表,它定义在EnumDataSpec::MANY_MANY_TABLE里，

    注意表类名头字母小写。
    主表类外键名称：【主表类名+"_"+id】，关系表类外键名称：【关系表类名+"_"+id】

说明：$field_spec_default为默认的数据对象的列规格说明，它全局的定义了当前应用的列规格说明；

####示例说明
示例1:用户详细信息定义列规格说明如下:

	public $field_spec=array(
		EnumDataSpec::FOREIGN_ID=>array(
			'country_r'=>"country",
			"province_r"=>"province",
			"city_r"=>"city",
			'district_r'=>"district"
		)
	);

示例2:用户角色列规格说明如下:

	public $field_spec=array(
		EnumDataSpec::REMOVE=>array(
			'commitTime',
			'updateTime'
		)
	);

###$has_one:一对一关系说明
####基本定义
相对于[2.3.数据库定义]表关系定义:一对一[has_one]

例如示例项目中

    主数据对象:User
    从数据对象:Userdetail
    用户[主数据对象]和用户详情[从数据对象]就是一对一关系


####示例说明
定义示例:

    class User extends DataObject {
    	static $has_one=array(
    		"userDetail"=> "UserDetail",
    	);
调用示例:

    $user=User::get_by_id(1);
    $userdetail=$user->userdetail;
    print_r($userdetail);


###$belong_has_one:从属一对一关系说明
####基本定义
相对于[2.3.数据库定义]表关系定义:从属于一对一[belong_has_one]

例如示例项目中

    主数据对象:User
    从数据对象:Userdetail
    用户详情[从数据对象]和用户[主数据对象]就是从属于一对一关系

####示例说明
定义示例:

    class Userdetail extends DataObject {
    	static $belongs_has_one=array(
    		"user"=>"User"
    	);
调用示例:

    $userdetail=Userdetail::get_by_id(1);
    $user=$userdetail->user;
    print_r($user);


###$has_many:一对多关系说明
####基本定义
相对于[2.3.数据库定义]表关系定义:一对多[has_many]

例如示例项目中

    主数据对象:Blog
    从数据对象:Comment
    博客[主数据对象]和评论[从数据对象]就是一对多关系


####示例说明
定义示例:

    class Blog extends DataObject {
    	static $has_many=array(
    		"comments"=> "Comment",
    	);
调用示例:

    $blog=Blog::get_by_id(1);
    $comments=$blog->comments;
    print_r($comments);


###$many_many:多对多关系说明
####基本定义
相对于[2.3.数据库定义]表关系定义:多对多[many_many]

例如示例项目中

    主数据对象:User
    从数据对象:Role
    多对多关系数据对象:Userrole
    用户[主数据对象]和角色[从数据对象]就是多对多关系

####示例说明
定义示例:

    class User extends DataObject {
    	static $many_many=array(
    		"roles"=> "Role",
    	);
调用示例:

    $user=User::get_by_id(1);
    $roles=$user->roles;
    print_r($roles);

###$belongs_many_many:从属于多对多关系说明
####基本定义
相对于[2.3.数据库定义]表关系定义:从属于多对多[belongs_many_many]

例如示例项目中

    主数据对象:User
    从数据对象:Role
    多对多关系数据对象:Userrole
    角色[从数据对象]和用户[主数据对象]就是从属于多对多关系

####示例说明
定义示例:

    class User extends DataObject {
    	static $has_one=array(
    		"userDetail"=> "UserDetail",
    	);

调用示例:

    $role=Role::get_by_id(1);
    $users=$role->users;
    print_r($users);

##更多说明
数据对象规格说明定义变量总共就5个,值得说明一下的是这些变量涉及的实现策略和规律总结，以下对其进行进一步的说明，有兴趣的朋友可以了解一下

###数据对象默认关键字定义
枚举类名称定义:EnumDataObjectDefaultKeyword

详细定义:
* NAME_FIELD_SPEC:field_spec

    自定义列规格说明的名称。

* NAME_IDNAME_STRATEGY:idname_strategy

    ID名称定义的策略的名称

* NAME_IDNAME_CONCAT:idname_concat

    ID名称中的连接符的名称

* NAME_FOREIGNIDNAME_STRATEGY:foreignid_name_strategy

    Foreign ID名称定义的策略的名称

* NAME_FOREIGNID_CONCAT:foreignid_concat

    Foreign ID名称中的连接符的名称,Foreign ID名称定义的策略为TABLENAME_ID有效

###数据库关联模式
枚举类名称定义:EnumTableRelation

详细定义:
* HAS_ONE:has_one

    一对一关联

* BELONG_HAS_ONE:belong_has_one

    从属一对一关联，即主表中一字段关联关系表中的主键

* HAS_MANY:has_many

    一对多关联

* MANY_MANY:many_many

    多对多关联

* BELONGS_TO:belongs_many_many

    从属多对多关联

###数据对象默认列定义
枚举类名称定义:EnumColumnNameDefault

详细定义:
* ID:id

    数据对象的唯一标识

* COMMITTIME:commitTime

    数据创建的时间

* UPDATETIME:updateTime

    数据最后更新的时间

###ID名称定义的策略
枚举类名称定义:EnumIDNameStrategy

详细定义:
* NONE:-1

    无策略;说明：需要在数据对象类里定义$field_spec；说明ID别名。

* ID:0

    ID名称为：id

* TABLENAMEID:1

    ID名称为:对象名+'id';如果对象名为User,则ID名称为:userid【头字母大小写均可】

* TABLENAME_ID:2

    ID名称为:对象名+连接符+'id';如果对象名为User,连接符为'_';则ID名称为:user_id【头字母大小写均可】

###数据对象列规格默认列定义
枚举类名称定义:EnumDataSpec

详细定义:
* REMOVE:remove

    数据对象定义中需要移除的列

* MANY_MANY_TABLE:many_many_table

    多对多关系表名称定义，如无定义，则按默认规则查找指定表。

* FOREIGN_ID:foreign_id

    数据对象外键名称定义，如无定义，则按默认规则查找指定外键。

