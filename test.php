<?php
require_once ("init.php");

$blog=Blog::get_one();
print_r($blog);
/*$blogs=Blog::select('name,content');
print_r($blogs);            
UtilBarCode::upc_a("12207201213");                                      

Blog::queryPage(0,10,
    array(
        //"(content like '%�抽�瀛�' or content like '%���璇�')",  
        "blog_id<4",
        "user_id"=>1
    ));
    
$user=new User();
$user->user_id=2;
print_r($user->userDetail);

print_r(User::get(array(username=> "like '%ad%'")));    
$serverCache=Manager_Cache::singleton()->server(EnumCacheDriverType::REDIS);
$serverCache->TestRun();
					       
print_r(SystemService::doLibrarySelect(array("name"=>"m")));                                             
LogMe::log("����充����锛��绛��");       
LogMe::log("瑁�繁娌��浣�氨瑁��锛�);

//PHP 涓�Linq
//Create data source
$names = array("John", "Peter", "Joe", "Patrick", "Donald", "Eric"); 

$result = from('$name')->in($names)
            ->where('$name => strlen($name) < 5')
            ->select('$name'); 
print_r($result);
			
//��浇��疆            
$xml=UtilConfig::Instance();
$xml->load(Gc::$nav_root_path."core\\util\\config\\xml\\"."setting.xml");
echo 'PHP:'. $xml->get('db.host').'';

//璋��涓��涓�$user=new User;
$user->setId(2);
print_r($user->getUserDetail());
print_r($user->UserDetail());

//璋��涓��澶�$department=new Department();
$department->setId(5);
print_r($department->getUsers());
print_r($department->Users());

//璋��澶��澶��涓绘�����广�
$user=new User();
$user->setId(2);
print_r($user->getRoles());
print_r($user->Roles());

//璋��澶��澶��琚������广�
$role=new Role();
$role->setId(2);
print_r($role->getUsers());
print_r($role->Users());
$joe=new User();
$joe->setPassword("tttt");
        $joe->setId($this->id);
$joe->setName("joy");
        $joe["name"]="wb";
$joe->save($joe);

$role=new Role();
$role->setId(5);
UtilDateTime::ChinaTime();
$role->saveRelationForManyToMany("users","6",array("commitTime"=>date("Y-m-d H:i:s"))); */

?> 
