<?php
    
/**
* 处理缓存的方式的类型                           
*/
class EnumCacheDriverType extends Enum{
    const MEMCACHE=0;
    const APC=1;    
}

/**
* 分布式缓存管理器
*/                
class Manager_Cache{                                      
    /**
    * 分布式缓存管理器唯一实例  
    * @var Manager_Cache
    */
    private static $manager_Cache;
    /**
    * 第一台缓存服务器
    * 
    * @var mixed
    */
    private static $server1;
    /**
    * 第二台缓存服务器
    * 
    * @var mixed
    */
    private static $server2;
    
    /**
     * 单例化
     * @return Manager_Db 
     */
    public static function newInstance() {
        if (self::$manager_Cache==null) {
            self::$manager_Cache=new Manager_Cache();
        }
        return self::$manager_Cache;
    }
 
       
    private function serverCache($cache_drive=EnumCacheDriverType::MEMCACHE,$host="127.0.0.1",$port="11211"){
        switch ($cache_drive){
           case  EnumCacheDriverType::MEMCACHE:
                return new Cache_Memcache($host,$port);  
           case  EnumCacheDriverType::MEMCACHE:
                return new Cache_Apc($host,$port);  
           default:
                return new Cache_Memcache($host,$port);      
        }
    }
    
    /**
    * 获取第一台缓存服务器
    * @param mixed $cache_drive 处理缓存的方式的类型,默认采用Memcache
    * @return Cache_Memcache
    */
    public function server1($cache_drive=EnumCacheDriverType::MEMCACHE){
        if (self::$server1==null) {
            self::$server1=$this->serverCache($cache_drive);
        }
        return self::$server1;
    }
    
    /**
    * 获取第一台缓存服务器
    * @param mixed $cache_drive 处理缓存的方式的类型,默认采用Memcache
    * @return Cache_Memcache
    */
    public function server2($cache_drive=EnumCacheDriverType::MEMCACHE){
        if (self::$server2==null) {
            self::$server2=$this->serverCache($cache_drive,"127.0.0.1","11211");
        }
        return self::$server2;
    }       
    
}  
?>
