<?php
/**
 +--------------------------------------------------<br/>
 * Http Session 会话管理类<br/>
 +--------------------------------------------------<br/>
 * @category betterlife
 * @package core.main
 * @author skygreen
 */
class HttpSession
{
    /**
     * 启动Session会话
     */
    public static function init()
    {
        session_start();   
    }

    /**
     * 判断Session中是否存在$key的值
     * 
     * @param mixed $key
     * @return mixed
     */
    public static function isHave($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * 在Session会话中添加指定$key的值
     * 
     * @param mixed $key
     * @param mixed $value
     */
    public static function set($key,$value)
    {
        $_SESSION[$key]= $value;
    }

    /**
     * 在Session会话中获取$key的值  
     * 
     * @param mixed $key
     * @return mixed
     */
    public static function get($key)
    {
        if (isset($_SESSION[$key])){
            return $_SESSION[$key];
        }else{
            return null;
        }
    }

    /**
     * 从Session会话中移除指定$key的值
     * 
     * @param string $key
     */
    public static function remove($key)
    {
        if(isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }                       
    }

    /**
     * 从Session会话中移除指定$keys的值
     * 
     * @param array $keys 多个键值
     */
    public static function removes($keys)
    {
        if ($keys&&is_array($keys)&&(count($keys)>0))
        {
            foreach ($keys as $key) {
                unset($_SESSION[$key]);
            }
        }                       
    }

    /**
     * 清除所有的Session 
     */
    public static function removeAll()
    {
        session_destroy();
    }
}
?>
