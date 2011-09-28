<?php
/*
 +---------------------------------<br/>
 * 使用Apc作为系统缓存。<br/>
 *  配置如下:
 *      extension=php_apc.dll
 *      [Cache Apc]
 *      apc.cache_by_default = Off
 *      apc.enable_cli = Off
 *      apc.enabled = On
 *      apc.file_update_protection = 2
 *      apc.filters = 
 *      apc.gc_ttl = 3600
 *      apc.include_once_override = Off
 *      apc.max_file_size = 1M
 *      apc.num_files_hint = 1000
 *      apc.optimization = Off
 *      apc.report_autofilter = Off
 *      apc.shm_segments = 1
 *      apc.shm_size = 30
 *      apc.slam_defense = 0
 *      apc.stat = On
 *      apc.ttl = 0
 *      apc.user_entries_hint = 100
 *      apc.user_ttl = 0
 *      apc.write_lock = On
 * 下载:http://downloads.php.net/pierre/  <br/>      
 * 下载[php5.2:http://downloads.php.net/pierre/php_apc-3.1.5-5.2-vc6-x86.zip]   <br/>     
 * 在Windows里安装APC:http://docs.moodle.org/20/en/Installing_APC_in_Windows    <br/>     
 * Problems with APC setup and apache2 errors:http://serverfault.com/questions/278530/problems-with-apc-setup-and-apache2-errors
 +---------------------------------
 * @category betterlife
 * @package core.cache
 * @author skygreen
 */
class Cache_Apc extends Cache_Base{

    public static $name = 'Alternative PHP Cache (APC)';
    public static $desc = 'The Alternative PHP Cache (APC) is a free and open opcode cache for PHP. It was conceived of to provide a free, open, and robust framework for caching and optimizing PHP intermediate code.';

   public function TestRun(){  
        $this->save("test","Hello boy"); 
        $this->set("test","Hello girl");  
        $test=$this->get("test");
        echo $test;
   }  
    
    public function Cache_Apc(){                     
    }
    
    /**
    * 在缓存里保存指定$key的数据<br/>
    * 仅当存储空间中不存在键相同的数据时才保存<br/>  
    * @param string $key
    * @param string|array|object $value
    * @param int $expired 过期时间，默认是1天；最高设置不能超过2592000(30天)
    * @return bool
    */ 
    public function save($key,$value){
        return apc_add($key, $value);  
    }

    /**
    * 在缓存里保存指定$key的数据 <br/>  
    * 与save和update不同，无论何时都保存 <br/>  
    * @param string $key
    * @param string|array|object $value
    * @param int $expired 过期时间，默认是1天；最高设置不能超过2592000(30天)
    * @return bool
    */
    public function set($key,$value){
        return apc_store($key, $value);  
    }
    
    /**
    * 在缓存里更新指定key的数据<br/>  
    * 仅当存储空间中存在键相同的数据时才保存<br/>  
    * @param string $key
    * @param string|array|object $value
    * @return bool
    */    
    public function update($key,$value)
    {          
         return apc_store($key, $value);     
    }
    /**
    * 获取指定key的值
    * @param string $key
    * @return string|array|object
    */
    public function get($key)
    {
        $data = apc_fetch($key);
        return $data;
    }     
    
    /**
    * 在缓存里删除指定$key的数据
    * @param string $key           
    * @return bool
    */    
    public function delete($key)
    { 
        return apc_delete($key);
    }
    
    
    /**
    * 清除所有的对象。                
    */
    public function clear()
    {
        return apc_clear_cache('user');
    }

    /**
    * 显示缓存服务器端状态信息
    * @param mixed $curBytes
    * @param mixed $totalBytes
    */
    public function status(&$curBytes,&$totalBytes){
        $minfo = apc_sma_info();
        $cinfo = apc_cache_info('user');
        foreach($minfo['block_lists'] as $c){
            $blocks[] = count($c);
        }

        $curBytes = $minfo['seg_size']-$minfo['avail_mem'];
        $totalBytes = $minfo['seg_size'];

        $return[] = array('name'=>'子系统运行时间','value'=>timeLength(time()-$cinfo['start_time']));
        $return[] = array('name'=>'可用内存','value'=>formatBytes($minfo['avail_mem']).' / '.formatBytes($minfo['seg_size']));
        $return[] = array('name'=>'内存使用方式','value'=>$cinfo['memory_type']);
        $return[] = array('name'=>'内存数据段','value'=>$minfo['num_seg'].'块 ('.implode(',',$blocks).')');
        $return[] = array('name'=>'缓存命中','value'=>$cinfo['num_hits'].'次');
        $return[] = array('name'=>'缓存未命中','value'=>$cinfo['num_misses'].'次');
        $return[] = array('name'=>'已缓存数据条数','value'=>$cinfo['num_entries'].'条');
        $return[] = array('name'=>'数据锁定方式','value'=>$cinfo['locking_type']);
        return $return;
    }
}
?>
