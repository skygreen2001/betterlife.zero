<?php
/*
 +---------------------------------<br/>
 * 使用Redis作为系统缓存。<br/>
 * 使用方法: 添加以下内容到Config_Memcache中<br/>
 *     所有的缓存服务器Memcache 主机IP地址和端口配置<br/>
 *     保存数据是否需要压缩。 <br/>        
 +---------------------------------    
 * @see Reference Method:http://code.google.com/p/phpredis/wiki/referencemethods
 * @see php-redis:http://code.google.com/p/php-redis/
 * @see PHP-redis中文说明:http://hi.baidu.com/%B4%AB%CB%B5%D6%D0%B5%C4%C8%CC%D5%DF%C3%A8/blog/item/c9ff4ac1898afa4fb219a8c7.html
 * @category betterlife
 * @package core.cache
 * @author skygreen
 */
class Cache_Redis extends Cache_Base
{ 
    private $redis;
    
    public function TestRun(){                                         
      $this->set('key', 'value');                                                        
      $this->update('key', 'hello,girl');
      $test=$this->get('key');   
      echo $test;
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
    }    
    
    public function Cache_Redis()
    {      
        $this->redis = new Redis();
        if (Config_Redis::$is_persistent){
            $this->redis->pconnect(Config_Redis::$host, Config_Redis::$port);  
        }else{
            $this->redis->connect(Config_Redis::$host, Config_Redis::$port);
        }
    }   

    /**  
     * 查看键key是否存在。   
     * @param string $key  
     */ 
    public function Contains($key)
    {
        if (isset($this->redis))
        {
            return  $this->redis->exists($key);
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
        if (is_object($value)){
            $value=serialize($value);
        }
        $this->redis->setnx($key, $value);   
        $now = time(NULL); // current timestamp
        $this->redis->expireAt($key, $now + $expired);         
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
        if (is_object($value)){
            $value=serialize($value);
        }
        $this->redis->setex($key,$expired,$value);        
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
        if (is_object($value)){
            $value=serialize($value);
        }
        $this->redis->setex($key,$expired,$value);         
    }
    
   /**
    * 在缓存里删除所有指定$key的数据
    * @param string|array $key           
    * @return bool
    */    
    public function delete($key)
    {       
        $this->redis->delete($key);                                            
    }
       
    
    /**
    * 获取指定key的值
    * @param string $key
    * @return string|array|object
    */
    public function get($key)
    {
        $data = $this->redis->get($key);
                                  
        if (@unserialize($data)){
            $data=unserialize($data);
        }
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
        $data = $this->redis->getMultiple($keyArr);
        if ($data){
            $result=array();
            foreach ($data as $element)
            {
                if(@unserialize($element)){
                   $element=unserialize($element); 
                }
                $result[]=$element;
            }                   
        }
        return $result;   
    }
    
    /**
    * 清除所有的对象。
    * 
    */
    public function clear()
    {       
        $allKeys = $this->redis->keys('*');    
        $this->delete($allKeys);    
    }
        
    
    public function close()
    {    
        $this->redis->close();
    }
    
    
    
    
    
    
    
    
    
    
    
}
?>
