<?php
require_once ("init.php");
//$blogs=Blog::select('name,content');
//print_r($blogs);                                                  

print_r(User::get(array(username=> "like '%ad%'")));    
//$serverCache=Manager_Cache::singleton()->server(EnumCacheDriverType::REDIS);
//$serverCache->TestRun();
					
//EnumCacheDriverType::REDIS
//print_r(SystemService::doLibrarySelect(array("name"=>"m")));                                             
//LogMe::log("我在想事情呢！等等我");       
//LogMe::log("装深沉，你就装吧！");

//PHP 与 Linq
// Create data source
//$names = array("John", "Peter", "Joe", "Patrick", "Donald", "Eric"); 

//$result = from('$name')->in($names)
//            ->where('$name => strlen($name) < 5')
//            ->select('$name'); 
//print_r($result);
			
//加载配置            
//$xml=UtilConfig::Instance();
//$xml->load(Gc::$nav_root_path."core\\util\\config\\xml\\"."setting.xml");
//echo 'PHP:'. $xml->get('db.host').'';

////调用一对一
//$user=new User;
//$user->setId(2);
//print_r($user->getUserDetail());
//print_r($user->UserDetail());

////调用一对多
//$department=new Department();
//$department->setId(5);
//print_r($department->getUsers());
//print_r($department->Users());
//
////调用多对多【主控的一方】
//$user=new User();
//$user->setId(2);
//print_r($user->getRoles());
//print_r($user->Roles());
//
////调用多对多【被控的一方】
//$role=new Role();
//$role->setId(2);
//print_r($role->getUsers());
//print_r($role->Users());
//$joe=new User();
//$joe->setPassword("tttt");
//        $joe->setId($this->id);
//$joe->setName("joy");
//        $joe["name"]="wb";
//$joe->save($joe);

//$role=new Role();
//$role->setId(5);
//UtilDateTime::ChinaTime();
//$role->saveRelationForManyToMany("users","6",array("commitTime"=>date("Y-m-d H:i:s")));

?> 
