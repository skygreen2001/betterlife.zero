<?php
/**
 +--------------------------------------------------<br/>
 * Http Cookie 管理类<br/>
 +--------------------------------------------------<br/>
 * @category betterlife
 * @package core.main
 * @author skygreen
 */
class HttpCookie
{
	/**
	 * 在Cookie中添加指定$key的值
	 * @param mixed $key
	 * @param string|array $value
	 * @param int $expire 过期时间。最小单位是秒，30天=60*60*24*30
	 */
	public static function set($key,$value,$expire=0)
	{
		if (is_array($value)){
			setcookie($key,json_encode($value),$expire);
		}else{
			setcookie($key,$value,$expire);
		}
	}
			
	/**
	 * 一次在Cookie中添加多个指定$key的值
	 * @param array $key_values 键值代表cookie里的名称值
	 * @param int $expire 过期时间。最小单位是秒，30天=60*60*24*30
	 */
	public static function sets($key_values,$expire=0)
	{
		foreach ($key_values as $key=>$value) {
			self::set($key,$value,$expire);
		}
	}
	
	/**
	 * 在Cookie中获取指定$key的值
	 * @param mixed $key
	 * @param mixed $returnType: 0-字符串，1-数组;默认返回字符串，
	 * @return 返回cookie值
	 */
	public static function get($key,$returnType=0)
	{
		if (isset($_COOKIE[$key])){
			if ($returnType){
				return json_decode($_COOKIE[$key],true);	
			}else{
				return $_COOKIE[$key];
			}
		}
		return "";
	}       
	 
	/**
	 * 一次在Cookie中获取多个指定$key的值
	 * @param mixed $keys 多个键值
	 * @param mixed $returnType: 0-字符串，1-数组;默认返回字符串，
	 * @return 返回cookie值
	 */
	public static function gets($keys,$returnType=0)
	{
		$result=array();
		if ($keys&&is_array($keys)&&(count($keys)>0))
		{
			foreach ($keys as $key) {
				$result[$key]=self::get($key,$returnType);
			}
		}            
		return $result;
	}
}
?>
