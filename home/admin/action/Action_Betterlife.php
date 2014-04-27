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
		 $this->loadExtJs('core/Admin.js');
	 }

	 /**
	  * 控制器:用户
	  */
	 public function user()
	 {
		 $this->init();
		 $this->ExtDirectMode();
		 $this->ExtUpload();
		 $this->loadExtJs('core/User.js');
         $this->load_onlineditor(array('Blog_Content','Comment'));
	 }

	 /**
	  * 控制器:博客
	  */
	 public function blog()
	 {
		 $this->init();
		 $this->ExtDirectMode();
		 $this->ExtUpload();
		 $this->loadExtJs('core/Blog.js');
         $this->load_onlineditor(array('Comment','Blog_Content'));
	 }
}
?>
