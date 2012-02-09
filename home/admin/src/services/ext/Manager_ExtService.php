<?php
/**
 +---------------------------------------<br/>
 * 服务类:所有ExtService的管理类<br/>
 +---------------------------------------
 * @category betterlife
 * @package web.back.admin.services
 * @subpackage ext   
 * @author skygreen skygreen2001@gmail.com
 */  
class Manager_ExtService extends Manager 
{        
	private static $blogService;
	/**
	 * 提供服务:博客
	 */
	public static function blogService()
	{
		if (self::$blogService==null) {
			self::$blogService=new ExtServiceBlog();
		}
		return self::$blogService;
	}

			 
}  
?>
