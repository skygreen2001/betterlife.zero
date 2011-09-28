<?php
    
/**
* 处理缓存的方式的类型                           
*/
class EnumCacheDriverType extends Enum{
    const MEMCACHE=0;
    const MEMCACHED=1;
    const MEMCACHED_CLIENT=2;
    const REDIS=3; 
    const APC=4;    
}

/**
* 分布式缓存管理器
*/                
class Manager_Cache extends Manager{                                      
    /**
    * 分布式缓存管理器唯一实例  
    * @var Manager_Cache
    */
    private static $instance;
    /**
    * 缓存服务器
    * 
    * @var mixed
    */
    private static $server;    
    
    private function __construct() 
    {
    }    
    
    /**
     * 单例化
     * @return Manager_Db 
     */
    public static function singleton() {
        if (!isset(self::$instance)){
            $c = __CLASS__;   
            self::$instance=new $c();
        }
        return self::$instance;
    }
 
       
    private function serverCache($cache_drive=EnumCacheDriverType::MEMCACHE){
        switch ($cache_drive){
           case  EnumCacheDriverType::MEMCACHE:
                return new Cache_Memcache();  
           case  EnumCacheDriverType::MEMCACHED_CLIENT:
                return new Cache_Memcached_Client();   
           case  EnumCacheDriverType::MEMCACHED:
                return new Cache_Memcached(); 
           case  EnumCacheDriverType::REDIS:
                return new Cache_Redis();    
           case  EnumCacheDriverType::APC:
                return new Cache_Apc();  
           default:
                return new Cache_Memcache();      
        }
    }
    
    /**
    * 获取缓存服务器
    * @param mixed $cache_drive 处理缓存的方式的类型,默认采用Memcache
    * @return Cache_Memcache
    */
    public function server($cache_drive=EnumCacheDriverType::MEMCACHE){
        if (self::$server==null) {
            self::$server=$this->serverCache($cache_drive);
        }
        return self::$server;
    }    
}  
?>
