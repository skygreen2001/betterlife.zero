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
