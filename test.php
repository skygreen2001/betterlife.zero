<?php
require_once ("init.php");

//调用一对一
// $user=User::get_by_id(1);
// $userdetail=$user->userdetail;
// print_r($user->userdetail);

// $user=new User();
// $user->user_id=1;
// print_r($user->userdetail);

//调用从属于一对一
// $userdetail=Userdetail::get_by_id(1);
// $user=$userdetail->user;
// print_r($user);

//调用从属于一对一[高级]
// $user=User::get_by_id(1);
// $userDetail=$user->userdetail;
// $region["country"] =$userDetail->country_r;
// $region["province"]=$userDetail->province_r;
// $region["city"] =$userDetail->city_r;
// $region["district"] =$userDetail->district_r;
// print_r($region);

//调用一对多
// $blog=Blog::get_by_id(1);
// $comments=$blog->comments;
// print_r($comments);

//调用多对多【主控的一方】
// $user=User::get_by_id(1);
// $roles=$user->roles;
// print_r($roles);

//调用多对多【被控的一方】
// $role=Role::get_by_id(1);
// $users=$role->users;
// print_r($users);

//父子关系
// $region=Region::get_by_id("2709");
// print_r($region);
// print_r($region->region_p);

//新增用户
// $user=new User();
// $user->setUsername("betterlife");
// $user->setPassword("123456");
// $user_id=$user->save();
// if($user_id) echo("新增用户标识:".$user_id); else echo("新增用户失败!");

//修改用户
// $user=User::get_by_id(3);
// $user["username"]="shanghai";
// $user->update();
// if($user) echo("修改用户信息成功!"); else echo("修改用户信息失败!");

//保存或更新用户信息
// $user=User::get_by_id(3);
// $user["username"]="shanghai";
// $user_id=$user->saveOrUpdate();
// if($user_id>1) echo("新增用户标识:".$user_id); elseif($user_id==true) echo("修改用户信息成功!");else  echo("修改用户信息失败!");

//删除用户信息
// $user=User::get_by_id(3);
// $isDelete=$user->delete();
// if($isDelete) echo("删除用户信息成功!"); else echo("删除用户信息失败!");

//更新用户指定的属性
// $isUpdate=User::updateProperties("1,2","loginTimes=100");
// if($isUpdate) echo("修改用户信息成功!"); else echo("修改用户信息失败!");

//根据条件更新用户指定的属性
// $isUpdate=User::updateBy("username='admin'","loginTimes=500");
// if($isUpdate) echo("修改用户信息成功!"); else echo("修改用户信息失败!");

//测试删除功能,预先存入10条数据
// for ($i=1; $i < 11; $i++) {
// 	$user=new User();
// 	$user->setUsername("betterlife".$i);
// 	$user->setPassword("123456");
// 	$user_id=$user->save();
// 	if($user_id) echo("新增用户标识:".$user_id); else echo("新增用户失败!");
// 	echo "<br/>";
// }
//删除指定标识的用户
// $isDelete=User::deleteByID(4);
// if($isDelete) echo("删除用户信息成功!"); else echo("删除用户信息失败!");
//删除多条指定标识的用户
// $isDelete=User::deleteByIds("5,6,7");
// if($isDelete) echo("删除用户信息成功!"); else echo("删除用户信息失败!");
//删除多条指定标识的用户
// $isDelete=User::deleteByIds("5,6,7");
// if($isDelete) echo("删除用户信息成功!"); else echo("删除用户信息失败!");
//删除指定条件的用户
// $isDelete=User::deleteBy("username='betterlife7'");
// if($isDelete) echo("删除用户信息成功!"); else echo("删除用户信息失败!");

//用户访问次数+5
// $isPlus=User::increment("user_id>1","loginTimes",5);
// if($isPlus) echo("用户访问次数+5成功!"); else echo("用户访问次数+5失败!");

//用户访问次数-3
// $isMinus=User::decrement("user_id>1","loginTimes",3);
// if($isMinus) echo("用户访问次数-3成功!"); else echo("用户访问次数-3失败!");

//查看标识为1的用户是否存在
// $isExist=User::existByID(1);
// if($isExist) echo("指定标识的用户存在!"); else echo("指定标识的用户不存在!");

//查看用户名为china的用户是否存在
// $isExist=User::existBy("username='china'");
// if($isExist) echo("用户名为china的用户存在!"); else echo("用户名为china的用户不存在!");

//查看博客名称列表
// $blog_names=Blog::select("blog_name");
// print_r($blog_names);

//查看一个博客名称
// $blog_name=Blog::select_one("blog_name");
// print_r($blog_name);

//查看博客列表
// $blogs=Blog::get();
// print_r($blogs);
// $users=User::get(array(username=> "like '%ad%'"));//查看用户名称包含有ad的用户
// print_r($users);

//查看一个博客
// $blog=Blog::get_one();
// print_r($blog);

// 查看指定标识的博客
// $blog=Blog::get_by_id(1);
// print_r($blog);

//查看博客的数量
// $countBlogs=Blog::count();
// echo($countBlogs);

//查看从第1到5条博客记录
// $blogs=Blog::queryPage(1,5);
// $blogs=Blog::queryPage(0,10,
// 	array(
// 		"(blog_content like '%关键字%' or blog_content like '%公开课%')",
// 		// "blog_id<4",
// 		// "user_id"=>1
// 	)
// );

//查看第一页的博客记录，每页3条记录，无查询条件
// $blogs=Blog::queryPageByPageNo(1,null,3);
// print_r($blogs);

//博客转换成xml字符串
// $blog=Blog::get_one();
// echo($blog->toXml());

//博客转换成Json字符串
// $blog=Blog::get_one();
// echo($blog->toJson());

//博客转换成数组
// $blog=Blog::get_one();
// print_r($blog->toArray());

//存储角色用户多对多关系
// $role=new Role();
// $role->role_id=5;
// $role->role_name="高级程序员";
// UtilDateTime::ChinaTime();
// $role->save();
// $role->saveRelationForManyToMany("users","1");
// print_r($role);

//获取用户访问次数最高的次数
// $max=User::max("loginTimes");
// echo($max);

//获取用户访问次数最低的次数
// $min=User::min("loginTimes");
// echo($min);

//获取用户访问次数的总和
// $sum=User::sum("loginTimes");
// echo($sum);

//获取博客名称含有Web的评论数
// $count=Comment::countMultitable("Blog a,Comment b","b.blog_id=a.blog_id and a.blog_name like '%Web%'");
// echo($count);

//获取博客名称含有Web的评论
// $comments=Comment::queryPageMultitable(1,6,"Blog a,Comment b","b.blog_id=a.blog_id and a.blog_name like '%Web%'");
// print_r($comments);


//PHP 与 Linq
//Create data source
// $names = array("John", "Peter", "Joe", "Patrick", "Donald", "Eric");

// $result = from('$name')->in($names)
//			 ->where('$name => strlen($name) < 5')
//			 ->select('$name');
// print_r($result);

//加载配置
// $xml=UtilConfig::Instance();
// $xml->load(Gc::$nav_root_path."core\\util\\config\\xml\\"."setting.xml");
// echo 'PHP:'. $xml->get('db.host').'';

// UtilBarCode::upc_a("12207201213");
// print_r(UtilDateTime::now(EnumDateTimeFORMAT::DATE,EnumDateTimeShow::TIME));

// $serverCache=Manager_Cache::singleton()->server(EnumCacheDriverType::REDIS);
// $serverCache->TestRun();

// LogMe::log("我在想事情呢！等等我");
// LogMe::log("不急不急,休息一下！");
?>
