<?php
/*
 +---------------------------------<br/>
 * 使用memcached作为系统缓存。<br/>
 * 使用方法: 添加以下内容到Config_Memcache中<br/>
 *     所有的缓存服务器Memcache 主机IP地址和端口配置<br/>
 *     保存数据是否需要压缩。 <br/>        
 +---------------------------------
 * @category betterlife
 * @package core.cache
 * @author skygreen
 */
class Cache_Memcache extends Cache_Base{   
    public static $name='Memcache';
    public static $desc='Memcache module provides handy procedural and object oriented interface to memcached, highly effective caching daemon, which was especially designed to decrease database load in dynamic web applications.';

    /**
    * 测试体验MemCache
    */
    public function TestRun()
    {                                                        
        $value="Hello World";
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
    
    public function Cache_Memcache($host="127.0.0.1",$port="11211")
    {         
        if(!class_exists('Memcache')){
            LogMe::log('请检查是否启动了Php Extensions:php_memcache',EnumLogLevel::ERR);
        }
        $this->obj = new Memcache;
        //加载所有的分布式服务器
        foreach  (Config_Memcache::$cache_servers as $host=>$port)
        {
            $this->obj->addServer($host, $port);                  
        }
        $this->obj->addServer('127.0.0.1', 11212);       
        $this->obj->addServer('127.0.0.1', 11213);       
//        if(!$this->obj->connect($host, $port)){
//            LogMe::log('不能连接上memcached服务器;Host:'.self::$host.",Port:".self::$port,EnumLogLevel::ERR);
//        }
        
        //$version = $this->obj->getVersion();
        //echo "Server's version: ".$version."<br/>\n";    
        parent::cachemgr();
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
        return $this->obj->add($key,$value,Config_Memcache::$is_compressed,$expired);
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
        return $this->obj->set($key,$value,Config_Memcache::$is_compressed,$expired);
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
        return $this->obj->replace($key, $value,Config_Memcache::$is_compressed,$expired);
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
        $data = $this->obj->get($keyArr);
        return $data;   
    }
    
    /**
    * 清除所有的对象。
    * 
    */
    public function clear()
    {
        return $this->obj->flush();
    }

    public function status(&$curBytes,&$totalBytes)
    {
        $info = $this->obj->getStats();
        $curBytes = $info['bytes'];
        $totalBytes = $info['limit_maxbytes'];

        $return[] = array('name'=>'子系统运行时间','value'=>timeLength($info['uptime']));
        $return[] = array('name'=>'缓存服务器','value'=>MEMCACHED_HOST.':'.MEMCACHED_PORT." (ver:{$info['version']})");
        $return[] = array('name'=>'数据读取','value'=>$info['cmd_get'].'次 '.formatBytes($info['bytes_written']));
        $return[] = array('name'=>'数据写入','value'=>$info['cmd_set'].'次 '.formatBytes($info['bytes_read']));
        $return[] = array('name'=>'缓存命中','value'=>$info['get_hits'].'次');
        $return[] = array('name'=>'缓存未命中','value'=>$info['get_misses'].'次');
        $return[] = array('name'=>'已缓存数据条数','value'=>$info['curr_items'].'条');
        $return[] = array('name'=>'进程数','value'=>$info['threads']);
        $return[] = array('value'=>$info['pid'],'name'=>'服务器进程ID');
        $return[] = array('value'=>$info['rusage_user'],'name'=>'该进程累计的用户时间(秒:微妙)');
        $return[] = array('value'=>$info['rusage_system'],'name'=>'该进程累计的系统时间(秒:微妙)');
        $return[] = array('value'=>$info['curr_items'],'name'=>'服务器当前存储的内容数量');
        $return[] = array('value'=>$info['total_items'],'name'=>'服务器启动以来存储过的内容总数');

//    $return[] = array('value'=>$info['curr_connections'],'name'=>'连接数量');
//    $return[] = array('value'=>$info['total_connections'],'name'=>'服务器运行以来接受的连接总数 ');
//    $return[] = array('value'=>$info['connection_structures'],'name'=>'服务器分配的连接结构的数量');
        return $return;
    }
    
    public function close()
    {
        $this->obj->close();
    }
}
?>