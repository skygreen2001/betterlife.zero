<?php
  /*
 +---------------------------------<br/>     
 * @todo
 * 使用memcached作为系统缓存。<br/>  
 * 基于libmemcached的客户端叫memcached，据说性能更好，功能也更多。<br/>                     
 * 暂未提供实现，该组件需要在Linux系统上方能使用。
 +---------------------------------
 * @see php的memcached客户端memcached:http://www.9enjoy.com/php-memcached/    
 * @category betterlife
 * @package core.cache
 * @author skygreen
 */
class Cache_Memcached extends Cache_Base
{
    

    /**
    * 测试体验MemCache
    */
    public function TestRun()
    {                                                        
        $value="Hello World";
        for($i=0;$i<100;$i++){
           $this->set($i,$i.":testrrrr".$value); 
        }                           
        
        //$mem->save("key", $value);        
        $this->update("key", "I'm a newbie!");                 
        $val = $this->get("key");            
        echo $val;        
        $this->update("key", array("Home"=>$value,"Guest"=>"I'm a newbie!"));             
        $val = $this->get("key");         
        print_r($val);  
        $this->delete("key");            
        $val = $this->get("key");
        echo $val;
        $member=new User();
        $member->setName("skygreen2001");
        $member->setPassword("administrator");  
        $member->setDepartmentId(3211);
        $this->save("Member1",$member);    
        $user=$this->get("Member1");                     
        echo $user;
        $member=new User();
        $member->setName("学必填");
        $member->setPassword("password");  
        $member->setDepartmentId(3212);
        $this->save("Member2",$member);          
            
        $users = $this->gets(Array('Member1', 'Member2'));  
        print_r($users);
        $this->clear();
        $this->close();
    }                                          
    
    public function Cache_Memcached()
    {         
        if(!class_exists('Memcached')){
            LogMe::log('请检查是否加载了LibMemcached,Memcached',EnumLogLevel::ERR);
        }
        $this->obj = new Memcached;        
        //加载所有的分布式服务器    
        $this->obj->addServers($cache_server[0], $cache_server[1]);                  
        parent::cachemgr();
    }
    
    
}  
?>
