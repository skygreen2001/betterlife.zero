<?php
/**
 +----------------------------------------------<br/>
 * 所有c采用Ext JS Javascript框架的控制器的父类<br/>
 * class_alias("Action","Controller");<br/>
 +----------------------------------------------
 * @category betterlife
 * @package core.model
 * @author skygreen
 */
class ActionExt extends Action 
{   
    /**
     * 加载Ext 第三方定义组件定义对象  
     */
    public function loadExtComponent($objectFile)
    {               
        $this->loadExtJs("components/$objectFile");  
    }      
                         
    /**
     * 加载Ext 显示层定义对象  
     * @param $viewFile 显示的文件路径
     * @param bool $isGzip 是否使用Gzip进行压缩。
     */
    public function loadExtJs($viewFile,$isGzip=false)
    {                
        if (UtilAjaxExtjs::$ext_version<4){
            $module_templateurl_relative="js/ext/"; 
        }else{
            $module_templateurl_relative="js/ext4/";   
        }
        $templateurl=$this->view->template_url; 
        if ($isGzip&&startWith($viewFile,'shared')){
            UtilJavascript::loadJsReady($this->view->viewObject, $viewFile,$isGzip,EnumJsFramework::JS_FW_EXTJS,UtilAjaxExtjs::$ext_version);  
        }else{
            $path=$templateurl.$module_templateurl_relative;       
            UtilJavascript::loadJsReady($this->view->viewObject, $path.$viewFile,$isGzip);  
        }             
        
    }     
    
    /**
     * 加载Ext 显示层Css文件      
     * @param $viewCss 显示的Css文件路径  
     * @param bool $isGzip 是否使用Gzip进行压缩。               
     */
    public function loadExtCss($viewCss,$isGzip=false)
    {
        $templateurl=$this->view->template_url; 
        $viewObject=$this->view->viewObject;
        if(empty($viewObject))
        {
            $this->view->viewObject=new ViewObject();
        }              
        if ($this->view->viewObject)
        {   
            if ($isGzip&&startWith($viewCss,'shared')){
                UtilCss::loadCssReady($this->view->viewObject,$viewCss,$isGzip,EnumJsFramework::JS_FW_EXTJS,UtilAjaxExtjs::$ext_version);  
            }else{
                UtilCss::loadCssReady($this->view->viewObject,$templateurl."resources/css/".$viewCss,$isGzip); 
            }
        }else{
            UtilCss::loadCss($templateurl."resources/css/".$viewCss,true); 
        }                
    }
    
    /**
     * 加载 组件的Css
     * @param $viewCss 显示的Css文件路径  
     * @param bool $isGzip 是否使用Gzip进行压缩。
     */
    public function loadExtComponentCss($viewCss,$isGzip=false)
    {
         $this->loadExtCss("shared/css/".$viewCss, $isGzip);         
    }
    
    /**
     * 使用Ext Direct Remote 模式
     */
    public function ExtDirectMode()
    {              
        UtilJavascript::loadJsReady($this->view->viewObject, "home/admin/src/services/ajax/extjs/direct/api.php");                         
    } 
    
    /**
     *  使用Ext 上传功能
     */
    public function ExtUpload()
    {
         $this->loadExtComponentCss("fileuploadfield.css"); 
         $this->loadExtComponent("FileUploadField.js"); 
    }
    
    /**
     * Ext请求返回 Response
     * @param mixed $response  
     * @param mixed $isFormAndIsUpload
     */
    public static function ExtResponse($response,$isFormAndIsUpload=true)
    {
        if ($isFormAndIsUpload) {
            echo '<html><body><textarea>';
            echo json_encode($response);
            echo '</textarea></body></html>';
        } else {
            echo json_encode($response);
        }
    }         
}
?>
