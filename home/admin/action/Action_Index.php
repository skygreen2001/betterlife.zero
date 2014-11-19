<?php
/**
 +---------------------------------<br/>
 * 控制器:网站后台管理首页|登录|登出<br/>
 +---------------------------------
 * @category betterlife
 * @package web.back.admin
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
		if(HttpSession::isHave(Gc::$appName_alias.'admin_id')) {
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
				HttpSession::sets(array('admin_id'=>$admindata->member_id,'admin'=>$admindata,'operator'=>$admindata->username));
				HttpSession::set(Gc::$appName_alias.'admin_id',$admindata->admin_id);
				HttpCookie::sets(array("admin_id"=>$admindata->admin_id,"operator"=>$admindata->username,'roletype'=>$admindata->roletype,'roleid'=>$admindata->roleid));
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
		$admin=HttpSession::get("admin");
		$roletype=$admin->roletype;
		if($roletype==EnumRoletype::SUPERADMIN){
			$this->view->menuGroups=MenuGroup::all();
		}else{
			$roletype=EnumRoletype::roletypeEnumKey($roletype);
			$roleMenugroups=MenuGroup::allrole();
			$this->view->menuGroups=$roleMenugroups[$roletype];
		}
	}

	/**
	 * 控制器:登出
	 */
	public function logout()
	{
		HttpSession::remove("admin_id");
		HttpSession::remove(Gc::$appName_alias."admin_id");
		$this->redirect("index","login");
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

			//顶部工具栏生成显示
			UtilJavascript::loadJsContentReady($viewobject,MenuToolbar::viewForExtJs());
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
			//顶部工具栏生成显示
			UtilJavascript::loadJsContent(MenuToolbar::viewForExtJs());
			//核心功能:导航[Tab新建窗口]
			UtilJavascript::loadJs($templateurl.$module_templateurl_relative."navigation.js",true);
		}
	 }
}
?>
