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

	/**
	 * 上传数据对象:地区数据文件
	 */
	public function uploadRegion()
	{
		return self::ExtResponse(Manager_ExtService::regionService()->import($_FILES));
	}

	/**
	 * 上传数据对象:评论数据文件
	 */
	public function uploadComment()
	{
		return self::ExtResponse(Manager_ExtService::commentService()->import($_FILES));
	}

	/**
	 * 上传数据对象:系统日志数据文件
	 */
	public function uploadLogsystem()
	{
		return self::ExtResponse(Manager_ExtService::logsystemService()->import($_FILES));
	}

	/**
	 * 上传数据对象:用户日志数据文件
	 */
	public function uploadLoguser()
	{
		return self::ExtResponse(Manager_ExtService::loguserService()->import($_FILES));
	}

	/**
	 * 上传数据对象:消息数据文件
	 */
	public function uploadMsg()
	{
		return self::ExtResponse(Manager_ExtService::msgService()->import($_FILES));
	}

	/**
	 * 上传数据对象:通知数据文件
	 */
	public function uploadNotice()
	{
		return self::ExtResponse(Manager_ExtService::noticeService()->import($_FILES));
	}

	/**
	 * 上传数据对象:用户收到通知数据文件
	 */
	public function uploadUsernotice()
	{
		return self::ExtResponse(Manager_ExtService::usernoticeService()->import($_FILES));
	}

	/**
	 * 上传数据对象:用户所属部门数据文件
	 */
	public function uploadDepartment()
	{
		return self::ExtResponse(Manager_ExtService::departmentService()->import($_FILES));
	}

	/**
	 * 上传数据对象:功能信息数据文件
	 */
	public function uploadFunctions()
	{
		return self::ExtResponse(Manager_ExtService::functionsService()->import($_FILES));
	}

	/**
	 * 上传数据对象:角色拥有功能数据文件
	 */
	public function uploadRolefunctions()
	{
		return self::ExtResponse(Manager_ExtService::rolefunctionsService()->import($_FILES));
	}

	/**
	 * 上传数据对象:用户角色数据文件
	 */
	public function uploadUserrole()
	{
		return self::ExtResponse(Manager_ExtService::userroleService()->import($_FILES));
	}

	/**
	 * 上传数据对象:角色数据文件
	 */
	public function uploadRole()
	{
		return self::ExtResponse(Manager_ExtService::roleService()->import($_FILES));
	}

	/**
	 * 上传数据对象:用户详细信息数据文件
	 */
	public function uploadUserdetail()
	{
		return self::ExtResponse(Manager_ExtService::userdetailService()->import($_FILES));
	}

	/**
	 * 批量上传用户详细信息图片:profile
	 */
	public function uploadUserdetailProfiles()
	{
		return self::ExtResponse(Manager_ExtService::userdetailService()->batchUploadImages($_FILES,"upload_profile_files","Userdetail","用户详细信息","profile"));
	}
}
?>
