<?php
/*
 +---------------------------------<br/>
 * 使用memcached作为系统缓存。<br/>
 * 使用方法: 添加以下内容到你的config.php中<br/>
 *     define('CACHE_METHOD','memcached');<br/>
 *     define('MEMCACHED_HOST',你的memcache服务器ip);<br/>
 *     define('MEMCACHED_PORT',memcache服务器端口);<br/>
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
    public static function TestRun(){
        $mem = new Cache_Memcache;                           
        $value="Hello World";
        //$mem->save("key", $value);        
        $mem->update("key", "I'm a newbie!");                 
        $val = $mem->get("key");            
        echo $val;        
        $mem->update("key", array("Home"=>$value,"Guest"=>"I'm a newbie!"));             
        $val = $mem->get("key");         
        print_r($val);  
        $mem->delete("key");            
        $val = $mem->get("key");         
        echo $val;
    }
    
    public function Cache_Memcache($host="127.0.0.1",$port="11211"){         
        if(!class_exists('Memcache')){
            LogMe::log('请检查是否启动了Php Extensions:php_memcache',EnumLogLevel::ERR);
        }
        $this->obj = new Memcache;
        if(!$this->obj->connect($host, $port)){
            LogMe::log('不能连接上memcached服务器;Host:'.self::$host.",Port:".self::$port,EnumLogLevel::ERR);
        }
        
        //$version = $this->obj->getVersion();
        //echo "Server's version: ".$version."<br/>\n";    
        parent::cachemgr();
    }
    
    /**
    * 在缓存里保存指定$key的数据
    * @param mixed $key
    * @param string|array $value
    * @return bool
    */
    public function save($key,$value){
        return $this->obj->set($key,$value);
    }

    /**
    * 在缓存里更新指定$key的数据
    * @param mixed $key
    * @param string|array $value
    * @return bool
    */    
    public function update($key,$value){
        $valueByKey= $this->get($key);
        if (empty($valueByKey)){
            return $this->save($key,$value);
        }
        //替换数据
        return $this->obj->replace($key, $value, 0, 60);
    }
    
   /**
    * 在缓存里删除指定$key的数据
    * @param mixed $key           
    * @return bool
    */    
    public function delete($key){    
        $this->obj->delete($key);                            
    }
    
    public function get($key){
        $data = $this->obj->get($key);
        return $data;
    }

    public function clear(){
        return $this->obj->flush();
    }

    public function status(&$curBytes,&$totalBytes){
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
}
?>