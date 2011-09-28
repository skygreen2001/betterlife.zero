<?php
/*
 +---------------------------------<br/>
 * 使用memcached作为系统缓存。<br/>                                                         
 +---------------------------------                                        
 * @see PHP & memcached:http://www.nioxiao.com/php-memcached
 * @category betterlife
 * @package core.cache
 * @author skygreen
 */
class Cache_Memcached_Client extends Cache_Base
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
        
        $this->save("key", $value);        
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
    
    public function Cache_Memcached_Client()
    {         
        if(!class_exists('Memcached_Client')){
            LogMe::log('请检查是否加载Memcached',EnumLogLevel::ERR);
        }                          
        $servers=array();     
        $config=array();  
        //加载所有的分布式服务器 
        foreach  (Config_Memcache::$cache_servers as $cache_server)
        {     
            $servers[]=$cache_server[0].":".$cache_server[1];                              
        }       
        $config['servers']=$servers;    
        if (Config_Memcache::$is_compressed){
           $config['compress_threshold']=10240;
        }
        $config['debug']=Gc::$dev_debug_on;
        $config['persistant']=Config_Memcache::$is_persistant;  
        $this->obj = new Memcached_Client($config);                                    
//        if(!$this->obj->connect($host, $port)){
//            LogMe::log('不能连接上memcached服务器;Host:'.self::$host.",Port:".self::$port,EnumLogLevel::ERR);
//        }
        
        //$version = $this->obj->getVersion();
        //echo "Server's version: ".$version."<br/>\n";    
        parent::cachemgr();
    }
    
    /**  
     * 查看键key是否存在。   
     * @param string $key  
     */ 
    public function Contains($key)
    {
        if (isset($this->obj)){
            $tmp=$this->get($key);
           if (!empty($tmp)){
             return true;
           }
        }
        return false;
    }
    
    /**
    * 在缓存里保存指定$key的数据<br/>
    * 仅当存储空间中不存在键相同的数据时才保存<br/>  
    * @param string $key
    * @param string|array|object $value
    * @param int $expired 过期时间，默认是1天；最高设置不能超过2592000(30天)
    * @return bool
    */ 
    public function save($key,$value,$expired=86400)
    {
        return $this->obj->add($key,$value,$expired);
    }
         
    /**
    * 在缓存里保存指定$key的数据 <br/>  
    * 与save和update不同，无论何时都保存 <br/>  
    * @param string $key
    * @param string|array|object $value
    * @param int $expired 过期时间，默认是1天；最高设置不能超过2592000(30天)
    * @return bool
    */
    public function set($key,$value,$expired=86400)
    {
        return $this->obj->set($key,$value,$expired);
    }

    /**
    * 在缓存里更新指定key的数据<br/>  
    * 仅当存储空间中存在键相同的数据时才保存<br/>  
    * @param string $key
    * @param string|array|object $value
    * @return bool
    */    
    public function update($key,$value,$expired=86400)
    {          
        //替换数据
        return $this->obj->replace($key, $value,$expired);
    }
    
   /**
    * 在缓存里删除指定$key的数据
    * @param string $key           
    * @return bool
    */    
    public function delete($key)
    {    
        $this->obj->delete($key);                            
    }
    
    /**
    * 获取指定key的值
    * @param string $key
    * @return string|array|object
    */
    public function get($key)
    {
        $data = $this->obj->get($key);
        return $data;
    }

    /**
    * 获取指定keys的值们。<br/>  
    * 允许一次查询多个键值，减少通讯次数。
    * @param array $key
    * @return array
    */
    public function gets($keyArr)
    {
        $data = $this->obj->get_multi($keyArr);
        return $data;   
    }
    
    /**
    * 清除所有的对象。                
    */
    public function clear()
    {                                 
    }                     
    
    /**
    * 关闭所有资源
    */
    public function close()
    {
        $this->obj->forget_dead_hosts ();
        $this->obj->disconnect_all ();
    }      
}
?>
