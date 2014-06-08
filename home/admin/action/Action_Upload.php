<?php
/**
 * 控制器:上传文件       
 * @category betterlife  
 * @package web.back.admin 
 * @subpackage action
 * @author skygreen skygreen2001@gmail.com   
 */
class Action_Upload extends ActionExt 
{         
	/**
	 * 上传数据对象:博客数据文件
	 */
	public function uploadBlog()
	{
		return self::ExtResponse(Manager_ExtService::blogService()->import($_FILES)); 
	}                

    /**
     * 上传数据对象:系统管理人员数据文件
     */
    public function uploadAdmin()
    {
        return self::ExtResponse(Manager_ExtService::adminService()->import($_FILES)); 
    }
	
    /**
     * 上传数据对象:用户数据文件
     */
    public function uploadUser()
    {
        return self::ExtResponse(Manager_ExtService::userService()->import($_FILES)); 
    }

}
?>
