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
		 $this->load_onlineditor('content');
	 }
}
?>