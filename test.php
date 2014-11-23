<?php
require_once ("init.php");
echo Wl::INFO_DB_CHARACTER;

// $region=Region::get_by_id("666");
// print_r($region);
// print_r($region->region_p);
/**
$user=User::get_by_id(1);
$userDetail=$user->userdetail;
print_r($userDetail);
$country=$userDetail->country_r;
$province=$userDetail->province_r;
$city=$userDetail->city_r;
$district=$userDetail->district_r;
print_r($country);
print_r($province);
print_r($city);
print_r($district);

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

$user=new User();
$user->user_id=2;
print_r($user->userdetail);
print_r(User::get(array(username=> "like '%ad%'")));

//调用一对一
$user=new User;
$user->setId(2);
print_r($user->getUserdetail());
print_r($user->Userdetail());

//调用一对多
$department=new Department();
$department->setId(5);
print_r($department->getUsers());
print_r($department->Users());

//调用多对多【主控的一方】
$user=new User();
$user->setId(2);
print_r($user->getRoles());
print_r($user->Roles());

//调用多对多【被控的一方】
$role=new Role();
$role->setId(2);
print_r($role->getUsers());
print_r($role->Users());
$joe=new User();
$joe->setUsername("joy");
$joe->setPassword("tttt");
$joe["username"]="wb";
$joe->save($joe);

$role=new Role();
$role->setId(5);
UtilDateTime::ChinaTime();
$role->saveRelationForManyToMany("users","6",array("commitTime"=>date("Y-m-d H:i:s"))); */

// UtilBarCode::upc_a("12207201213");
// print_r(UtilDateTime::now(EnumDateTimeFORMAT::DATE,EnumDateTimeShow::TIME));

// $serverCache=Manager_Cache::singleton()->server(EnumCacheDriverType::REDIS);
// $serverCache->TestRun();

//PHP 与 Linq
//Create data source
// $names = array("John", "Peter", "Joe", "Patrick", "Donald", "Eric");

// $result = from('$name')->in($names)
//			 ->where('$name => strlen($name) < 5')
//			 ->select('$name');
// print_r($result);

// LogMe::log("我在想事情呢！等等我");
// LogMe::log("装深沉，你就装吧！");

// //加载配置
// $xml=UtilConfig::Instance();
// $xml->load(Gc::$nav_root_path."core\\util\\config\\xml\\"."setting.xml");
// echo 'PHP:'. $xml->get('db.host').'';

?>
