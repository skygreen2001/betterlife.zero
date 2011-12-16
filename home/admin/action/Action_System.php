<?php
/**
 +---------------------------------<br/>
 * 控制器:网站后台系统管理类<br/>
 +---------------------------------
 * @category betterlife
 * @package  web.back.admin
 * @subpackage action
 * @author skygreen
 */
class Action_System extends Action
{
    /**
     * 网站系统文件管理
     */
   public function filemanager()
   {
       if (isset($this->data["module"])){
           $module=$this->data["module"];
       }else{
           $module="source";
       }
       switch ($module){
           case "source":
               $redirect_module_url="tools/file/viewfiles.php";
               break;
           case "image":
               $redirect_module_url="tools/file/imagefileupload/edit.php";
               break;
           case "files":
               $redirect_module_url="tools/file/FileManager/index.php";
               break;           
           default:
               $redirect_module_url="tools/file/viewfiles.php";
               break;
       }
       
       $this->view->redirect_module_url=$redirect_module_url;
       $this->view->module=$module;
   }
    
   /**
    * 系统性能探针
    */
   public function probe()
   {       
       if (isset($this->data["module"])){
           $module=$this->data["module"];
       }else{
           $module="bCheck";
       }
       
       switch ($module){
           case "probe":
               $redirect_module_url="tools/probe/iproberphp/iProber.php";
               break;     
           case "probe1":
               $redirect_module_url="tools/probe/iproberphp/iProber1.php";
               break;  
           case "probe2":
               $redirect_module_url="tools/probe/iproberphp/iProber2.php";
               break;    
           default:
               $redirect_module_url="tools/probe/phpprobe.php";
               break;
       }
       $this->view->redirect_module_url=$redirect_module_url;
       $this->view->module=$module;
   }
        
   /**
    * 菜单管理
    */
   public function menumanager()
   {
        //初始化加载Css和Javascript库
        $this->view->viewObject=new ViewObject();
        UtilCss::loadExt($this->view->viewObject,UtilAjaxExtjs::$ext_version);               
        UtilAjaxExtjs::load(UtilAjaxExtjs::$ext_version,$this->view->viewObject);  
        
        $templateurl=$this->view->template_url;        
        if (UtilAjaxExtjs::$ext_version<4){
            $module_templateurl_relative="js/ext/"; 
        }else{
            $module_templateurl_relative="js/ext4/";   
        }
        UtilJavascript::loadJsReady($this->view->viewObject,"shared/grid/roweditor.js",true,EnumJsFramework::JS_FW_EXTJS,UtilAjaxExtjs::$ext_version); 
        UtilJavascript::loadJsReady($this->view->viewObject,$templateurl.$module_templateurl_relative."system/menu.js");                               
        UtilJavascript::loadJsReady($this->view->viewObject,"home/admin/src/services/ajax/extjs/direct/api.php");     
        
   }
   
   /**
    * 第三方库加载
    */
   public function librarymanager()
   {        
        //初始化加载Css和Javascript库
        $this->view->viewObject=new ViewObject();
        UtilCss::loadExt($this->view->viewObject,UtilAjaxExtjs::$ext_version);
        UtilAjaxExtjs::loadUI($this->view->viewObject,UtilAjaxExtjs::$ext_version);  
        
        $templateurl=$this->view->template_url;        
        if (UtilAjaxExtjs::$ext_version<4){
            $module_templateurl_relative="js/ext/"; 
        }else{
            $module_templateurl_relative="js/ext4/";   
        }
        UtilCss::loadCssReady($this->view->viewObject,$templateurl."resources/css/library.css",true);       
        UtilJavascript::loadJsReady($this->view->viewObject,"shared/message.js",true,EnumJsFramework::JS_FW_EXTJS,UtilAjaxExtjs::$ext_version);        
        UtilJavascript::loadJsReady($this->view->viewObject,"shared/grid/roweditor.js",true,EnumJsFramework::JS_FW_EXTJS,UtilAjaxExtjs::$ext_version);
        UtilJavascript::loadJsReady($this->view->viewObject,"shared/grid/checkcolumn.js",true,EnumJsFramework::JS_FW_EXTJS,UtilAjaxExtjs::$ext_version);          
        UtilJavascript::loadJsReady($this->view->viewObject,$templateurl.$module_templateurl_relative."system/library.js");          
   }                 
   
   /**
    * 功能模块加载
    */
   public function modulemanager()
   {
       
   }   
}

?>
