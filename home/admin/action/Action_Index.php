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
class Action_Index extends ActionExt
{
	/**
	 * 控制器:登录
	 */    
	public function login()
	{
		if(HttpSession::isHave('admin_id')) {
			$this->redirect("index","index");
		}
		$this->loadCss("resources/css/login.css");  
		UtilJavascript::loadJsReady($this->view->viewObject,Gc::$url_base."common/js/ajax/jquery/jquery-1.7.1.js");
		$this->loadJs("js/login.js");   
		if (!empty($_POST)) {     
			if (HttpSession::get("validate")!= md5($this->data["validate"])){
				$this->view->set("message","图形验证码输入错误");
				return;
			}            
			$admin = $this->model->Admin;
			$admindata = Admin::get_one($admin);
			if (empty($admindata)) {
				$this->view->set("message","用户名或者密码错误");
			}else {
				HttpSession::set('admin_id',$admindata->admin_id);
				$this->redirect("index","index");
			}
		}
	}
	 /**
	  * 控制器:首页
	  */
	 public function index()
	 {        
		 $this->init();
		 $this->loadIndexJs();              
		 //加载菜单
		 $this->view->menuGroups=MenuGroup::all();
	 }
	 
	/**
	 * 控制器:登出
	 */    
	public function logout()
	{
	  HttpSession::remove("admin_id");
	  $this->redirect("index","login");
	}

	 /**
	  * 控制器:系统管理人员
	  */
	 public function admin()
	 {
		 $this->init();
		 $this->ExtDirectMode();
		 $this->ExtUpload();
		 $this->loadExtJs('admin/admin.js');
	 }
	 
	 /**
	  * 控制器:用户
	  */
	 public function user()
	 {
		 $this->init();
		 $this->ExtDirectMode();
		 $this->ExtUpload();
		 $this->loadExtJs('user/user.js');
	 }
	
	 /**
	  * 控制器:博客
	  */
	 public function blog()
	 {
		 $this->init();
		 $this->ExtDirectMode();
		 $this->ExtUpload();
		 $this->loadExtJs('blog/blog.js');
		 $this->load_onlineditor('content');
	 }
	 
	 /**
	  * 初始化，加载Css和Javascript库。
	  */
	 private function init()
	 {
		 //初始化加载Css和Javascript库
		 $this->view->viewObject=new ViewObject();
		 UtilCss::loadExt($this->view->viewObject,UtilAjaxExtjs::$ext_version);
		 UtilAjaxExtjs::loadUI($this->view->viewObject,UtilAjaxExtjs::$ext_version);         
	 }
	 
	 /**
	  * 预加载首页JS定义库。
	  * @param ViewObject $viewobject 表示层显示对象
	  * @param string $templateurl
	  */
	 private function loadIndexJs()
	 {                                          
		$viewobject=$this->view->viewObject;  
		$this->loadExtCss("index.css",true);    
		if ($viewobject)
		{
			$this->loadExtJs("index.js",true);                                                                
			//核心功能:外观展示             
			$this->loadExtJs("layout.js",true); 
			//左侧菜单组生成显示
			UtilJavascript::loadJsContentReady($viewobject,MenuGroup::viewForExtJs());  
			//核心功能:导航[Tab新建窗口]
			$this->loadExtJs("navigation.js",true);  
		}
		else
		{
			$templateurl=$this->view->template_url;
			if (UtilAjaxExtjs::$ext_version<4)
			{
				$module_templateurl_relative="js/ext/";        
			}else{
				$module_templateurl_relative="js/ext4/";  
			}                 
			UtilJavascript::loadJs($templateurl.$module_templateurl_relative."index.js",true); 
			//核心功能:外观展示
			UtilJavascript::loadJs($templateurl.$module_templateurl_relative."layout.js",true);              
			//左侧菜单组生成显示
			UtilJavascript::loadJsContent(MenuGroup::viewForExtJs());  
			//核心功能:导航[Tab新建窗口]
			UtilJavascript::loadJs($templateurl.$module_templateurl_relative."navigation.js",true);      
		}
	 }
}
?>
