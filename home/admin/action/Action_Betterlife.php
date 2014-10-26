<?php
/**
 +---------------------------------<br/>
 * 控制器:网站后台管理首页<br/>
 +---------------------------------
 * @category betterlife
 * @package  web.back.admin
 * @subpackage action
 * @author skygreen
 */
class Action_Betterlife extends ActionExt
{
	/**
	 * 控制器:系统管理人员
	 */
	public function admin()
	{
		$this->init();
		$this->ExtDirectMode();
		$this->ExtUpload();
		$this->loadExtJs('core/admin.js');
	}
	/**
	 * 控制器:用户
	 */
	public function user()
	{
		$this->init();
		$this->ExtDirectMode();
		$this->ExtUpload();
		$this->loadExtJs('core/user.js');
		$this->load_onlineditor(array('blog_content','comment','log_content'));
	}
	/**
	 * 控制器:博客
	 */
	public function blog()
	{
		$this->init();
		$this->ExtDirectMode();
		$this->ExtUpload();
		$this->loadExtJs('core/blog.js');
		$this->load_onlineditor(array('comment','blog_content'));
	}
	/**
	 * 控制器:地区
	 */
	public function region()
	{
		$this->init();
		$this->ExtDirectMode();
		$this->ExtUpload();
		$this->loadExtComponent("ComboBoxTree.js");
		$this->loadExtJs('core/region.js');
	}

	/**
	 * 控制器:评论
	 */
	public function comment()
	{
		$this->init();
		$this->ExtDirectMode();
		$this->ExtUpload();
		$this->loadExtJs('core/comment.js');
		$this->load_onlineditor('comment');
	}

	/**
	 * 控制器:系统日志
	 */
	public function logsystem()
	{
		$this->init();
		$this->ExtDirectMode();
		$this->ExtUpload();
		$this->loadExtJs('core/logsystem.js');
	}

	/**
	 * 控制器:用户日志
	 */
	public function loguser()
	{
		$this->init();
		$this->ExtDirectMode();
		$this->ExtUpload();
		$this->loadExtJs('core/loguser.js');
		$this->load_onlineditor('log_content');
	}

	/**
	 * 控制器:消息
	 */
	public function msg()
	{
		$this->init();
		$this->ExtDirectMode();
		$this->ExtUpload();
		$this->loadExtJs('core/msg.js');
		$this->load_onlineditor('content');
	}

	/**
	 * 控制器:通知
	 */
	public function notice()
	{
		$this->init();
		$this->ExtDirectMode();
		$this->ExtUpload();
		$this->loadExtJs('core/notice.js');
		$this->load_onlineditor('notice_content');
	}

	/**
	 * 控制器:用户收到通知
	 */
	public function usernotice()
	{
		$this->init();
		$this->ExtDirectMode();
		$this->ExtUpload();
		$this->loadExtJs('core/usernotice.js');
	}

	/**
	 * 控制器:用户所属部门
	 */
	public function department()
	{
		$this->init();
		$this->ExtDirectMode();
		$this->ExtUpload();
		$this->loadExtJs('core/department.js');
	}

	/**
	 * 控制器:功能信息
	 */
	public function functions()
	{
		$this->init();
		$this->ExtDirectMode();
		$this->ExtUpload();
		$this->loadExtJs('core/functions.js');
	}

	/**
	 * 控制器:角色拥有功能
	 */
	public function rolefunctions()
	{
		$this->init();
		$this->ExtDirectMode();
		$this->ExtUpload();
		$this->loadExtJs('core/rolefunctions.js');
	}

	/**
	 * 控制器:用户角色
	 */
	public function userrole()
	{
		$this->init();
		$this->ExtDirectMode();
		$this->ExtUpload();
		$this->loadExtJs('core/userrole.js');
	}

	/**
	 * 控制器:角色
	 */
	public function role()
	{
		$this->init();
		$this->ExtDirectMode();
		$this->ExtUpload();
		$this->loadExtJs('core/role.js');
	}

	/**
	 * 控制器:用户详细信息
	 */
	public function userdetail()
	{
		$this->init();
		$this->ExtDirectMode();
		$this->ExtUpload();
		$this->loadExtJs('core/userdetail.js');
	}
}
?>
