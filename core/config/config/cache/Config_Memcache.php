<?php     
/**
 +---------------------------------<br/>
 * 分布式缓存:Memcache的配置类<br/>
 * 应根据项目的需要修改相应的配置<br/>
 +---------------------------------<br/>      
 *基本：
 *    MemCacheD Manager：http://allegiance.chi-town.com/MemCacheDManager.aspx
 *    .NET memcached client library：http://memcacheddotnet.sourceforge.net/    
 *    memcached 1.2.6：http://code.jellycan.com/memcached/
 *    Official       ：http://memcached.org/
 *    memcached for Windows：http://splinedancer.com/memcached-win32/ 
 *使用：
 *    Memcache基础教程：http://www.ccvita.com/259.html
 *    PHP中的Memcache函数库：http://lizi.blogbus.com/logs/17612967.html
 *    使用Memcached提高.NET应用程序的性能：http://zhoufoxcn.blog.51cto.com/792419/528212
 *    Windows下配置PHP+Memcache：http://www.redtamo.com/memcache/windows_php_memcache.html
 *高级：
 *    memcachedを知り尽くす:http://gihyo.jp/dev/feature/01/memcached/
 *    PHP与Perl操作Memcached速度差异比较:http://hiadmin.com/?cat=343
 *    创建多个Memcache       :http://hi.baidu.com/devnotes/blog/item/84cea312014df824dd5401c5.html
 *    Memcache mutex设计模式 :http://timyang.net/programming/memcache-mutex/
 *    Memcached深度分析(原创):http://blog.developers.api.sina.com.cn/?p=124
 +---------------------------------<br/>
 * @category betterlife
 * @package core.config
 * @subpackage cache
 * @author skygreen
 */
class Config_Memcache extends ConfigBB 
{   
    /**
    * 所有的缓存服务器
    * 这里是在同一台机器上运行3个Memcache。
    * 可以根据需要将其设置成多台机器上得MemCache。
    * @var mixed
    */
    public static $cache_servers=array(
        '127.0.0.1'=>11211,
        '127.0.0.1'=>11212,
        '127.0.0.1'=>11213,
    );

    /**
    * 存储数据是否采用压缩格式(需要使用zlib)。   
    * @var mixed
    */
    public static $is_compressed=false;    
    
}
?>                                                     

