<?php
require_once ("init.php");
// echo Wl::INFO_DB_CHARACTER;

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
// if($user) echo("修改用户成功!"); else echo("修改用户失败!");

//保存或更新用户信息
$user=User::get_by_id(3);
$user["username"]="shanghai";
$user_id=$user->saveOrUpdate();
if($user_id>1) echo("新增用户标识:".$user_id); elseif($user_id>1) echo("修改用户成功!");else  echo("修改用户失败!");




// $countBlogs=Blog::count();
// echo($countBlogs);

//分页方法调用
// $blogs=Blog::queryPage(1,5);
// $blogs=Blog::queryPageByPageNo(1,null,3);
// print_r($blogs);


/**
$blogs=Blog::select('blog_name,blog_content');
print_r($blogs);

$blog=Blog::get_one();
print_r($blog);

Blog::queryPage(0,10,
	array(
		//"(blog_content like '%关键字%' or blog_content like '%公开课%')",
		"blog_id<4",
		"user_id"=>1
	)
);

print_r(User::get(array(username=> "like '%ad%'")));

$role=new Role();
$role->setId(5);
UtilDateTime::ChinaTime();
$role->saveRelationForManyToMany("users","6",array("commitTime"=>date("Y-m-d H:i:s"))); */


//PHP 与 Linq
//Create data source
// $names = array("John", "Peter", "Joe", "Patrick", "Donald", "Eric");

// $result = from('$name')->in($names)
//			 ->where('$name => strlen($name) < 5')
//			 ->select('$name');
// print_r($result);


// //加载配置
// $xml=UtilConfig::Instance();
// $xml->load(Gc::$nav_root_path."core\\util\\config\\xml\\"."setting.xml");
// echo 'PHP:'. $xml->get('db.host').'';

// UtilBarCode::upc_a("12207201213");
// print_r(UtilDateTime::now(EnumDateTimeFORMAT::DATE,EnumDateTimeShow::TIME));

// $serverCache=Manager_Cache::singleton()->server(EnumCacheDriverType::REDIS);
// $serverCache->TestRun();

// LogMe::log("我在想事情呢！等等我");
// LogMe::log("装深沉，你就装吧！");
?>
