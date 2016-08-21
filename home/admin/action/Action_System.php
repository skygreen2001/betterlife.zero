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
class Action_System extends ActionExt
{
    /**
     * 网站系统文件管理
     */
    public function filemanager()
    {
        $module=$this->data->module;
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
        $this->loadExtComponentCss("RowEditor.css");
        UtilAjaxExtjs::load(UtilAjaxExtjs::$ext_version,$this->view->viewObject);
        $this->loadExtJs("shared/grid/roweditor.js",true);
        $this->loadExtJs("system/menu.js");
        $this -> ExtDirectMode();
    }

    /**
     * 第三方库加载
     */
    public function librarymanager()
    {
        //初始化加载Css和Javascript库
        $this->view->viewObject=new ViewObject();
        UtilCss::loadExt($this->view->viewObject,UtilAjaxExtjs::$ext_version);
        $this->loadExtComponentCss("RowEditor.css");
        $this->loadExtCss("library.css",true);
        UtilAjaxExtjs::load(UtilAjaxExtjs::$ext_version,$this->view->viewObject);
        $this->loadExtJs("shared/message.js",true);
        $this->loadExtJs("shared/grid/roweditor.js",true);
        $this->loadExtJs("shared/grid/checkcolumn.js",true);
        $this->loadExtJs("system/library.js");
    }

    /**
     * 功能模块加载
     */
    public function modulemanager()
    {
        //初始化加载Css和Javascript库
        $this->view->viewObject=new ViewObject();
        UtilCss::loadExt($this->view->viewObject,UtilAjaxExtjs::$ext_version);
        $this->loadExtComponentCss("RowEditor.css");
        UtilAjaxExtjs::load(UtilAjaxExtjs::$ext_version,$this->view->viewObject);
        $this->loadExtJs("shared/message.js",true);
        $this->loadExtJs("shared/grid/roweditor.js",true);
        $this->loadExtJs("shared/grid/checkcolumn.js",true);
        $this->loadExtJs("system/module.js");
        $this -> ExtDirectMode();
    }
}

?>
