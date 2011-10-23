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
class Action_Index extends Action
{
     /**
      * 控制器:首页
      */
     public function index()
     {        
         $this->init();
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
         $this->loadCss();
         $this->loadIndexJs();
         //加载菜单
         $this->view->menuGroups=MenuGroup::all();
     }
     
     /**
      * 预加载首页JS定义库。
      * @param ViewObject $viewobject 表示层显示对象
      * @param string $templateurl
      */
     private function loadIndexJs()
     {
        $templateurl=$this->view->template_url;
        $viewobject=$this->view->viewObject;
        if (UtilAjaxExtjs::$ext_version<4)
        {
            $module_templateurl_relative="js/ext/";        
        }else{
            $module_templateurl_relative="js/ext4/";  
        }            
        if ($viewobject)
        {
            UtilJavascript::loadJsReady($viewobject,$templateurl.$module_templateurl_relative."index.js"); 
            //核心功能:外观展示
            UtilJavascript::loadJsReady($viewobject,$templateurl.$module_templateurl_relative."layout.js",true); 
            //左侧菜单组生成显示
            UtilJavascript::loadJsContentReady($viewobject,MenuGroup::viewForExtJs());  
            //核心功能:导航[Tab新建窗口]
            UtilJavascript::loadJsReady($viewobject,$templateurl.$module_templateurl_relative."navigation.js");  
        }
        else
        {
            UtilJavascript::loadJs($templateurl.$module_templateurl_relative."index.js"); 
            //核心功能:外观展示
            UtilJavascript::loadJs($templateurl.$module_templateurl_relative."layout.js",true);              
            //左侧菜单组生成显示
            UtilJavascript::loadJsContent(MenuGroup::viewForExtJs());  
            //核心功能:导航[Tab新建窗口]
            UtilJavascript::loadJs($templateurl.$module_templateurl_relative."navigation.js");      
        }
     }
}
?>
