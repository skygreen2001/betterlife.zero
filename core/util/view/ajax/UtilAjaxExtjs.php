<?php

/**
 +---------------------------------<br/>
 * 工具类：ExtJs[Javascript Ajax 框架]<br/>
 +---------------------------------
 * @category betterlife
 * @package util.view.ajax
 * @author skygreen
 */
class UtilAjaxExtjs extends UtilAjax implements IUtilAjax
{
    /**
     * Ext Js框架的版本号
     * @var type 
     */
    public static $ext_version="3.3.0";
    /**
     * 动态加载ExtJS:Ajax Javascript Framework库
     * @link http://www.sencha.com/products/extjs/
     * @link https://ajax.googleapis.com/ajax/libs/ext-core/3.1.0/ext-core.js
     * @param string $version javascript框架的版本号
     * @param ViewObject $viewObject 表示层显示对象,只在Web框架中使用,一般结合loadJsReady使用
     */
    public static function load($version="3.3.0",$viewObject=null) 
    {   
        if (self::$IsGoogleApi){
            if ($viewObject)
            {
                self::loadJsReady($viewObject,"https://ajax.googleapis.com/ajax/libs/ext-core/$version/ext-core.js");
            }else{
                self::loadJs("https://ajax.googleapis.com/ajax/libs/ext-core/$version/ext-core.js");
            }
        }else{
            if ($version<4)
            {
                $ajax_root="common/js/ajax/ext/";  
                if ($viewObject){
                    self::loadJsReady($viewObject,$ajax_root."adapter/ext/ext-base.js");
                }else{
                    self::loadJs($ajax_root."adapter/ext/ext-base.js");
                }
            }else{
                $ajax_root="common/js/ajax/ext4/";   
            }
            if (self::$IsDebug){
                if ($viewObject)
                {
                    self::loadJsReady($viewObject,$ajax_root."ext-all-debug-w-comments.js");
                }else{
                    self::loadJs($ajax_root."ext-all-debug-w-comments.js");                
                }
            }else{
                if ($viewObject)
                {
                    self::loadJsReady($viewObject,$ajax_root."ext-all.js");
                }else{
                    self::loadJs($ajax_root."ext-all.js");                
                }
            }
            self::loadJs("locale/ext-lang-zh_CN.js",true,EnumJsFramework::JS_FW_EXTJS,$version,$viewObject);
        }
    }
    
    /**
     * 动态加载ExtJS:Ajax Javascript Framework库
     * @param ViewObject $viewObject 表示层显示对象
     * @param string $version javascript框架的版本号
     */
    public static function loadUI($viewObject=null,$version="3.3.0") 
    {  
        self::load($version,$viewObject);
        if ($viewObject){
            //Tab头部右键关闭功能
            self::loadJsReady($viewObject,"shared/TabCloseMenu.js",true,EnumJsFramework::JS_FW_EXTJS,$version);  
            //Tab头部右侧多Tab下拉选择Tab页菜单
            self::loadJsReady($viewObject,"shared/tabscroller/TabScrollerMenu.js",true,EnumJsFramework::JS_FW_EXTJS,$version);
            //在Tab头部右侧添加按钮,用于3.3.0版本中
            //self::loadJsReady("shared/AddTabButton.js",true,EnumJsFramework::JS_FW_EXTJS,$version); 
            //self::loadJsReady($viewobject,"shared/XmlTreeLoader.js",true,EnumJsFramework::JS_FW_EXTJS,$version); 
        }else{
            //Tab头部右键关闭功能
            self::loadJs("shared/TabCloseMenu.js",true,EnumJsFramework::JS_FW_EXTJS,$version);
            //在Tab头部右侧添加按钮
            //self::loadJs("shared/AddTabButton.js",true,EnumJsFramework::JS_FW_EXTJS,$version);             
            self::loadJs("shared/tabscroller/TabScrollerMenu.js",true,EnumJsFramework::JS_FW_EXTJS,$version);
            //self::loadJs($viewobject,"shared/XmlTreeLoader.js",true,EnumJsFramework::JS_FW_EXTJS,$version);             
        }
    }
    
    /**
     * 发送Ajax请求的语句
     * @param string $url 通信的Url地址。
     * @param array $dataArray 传送的数据。
     * @param array $method HTTP的标准协议动词【get|post|put|delete】。
     * @param enum $response_type 返回的数据类型
     * @param string $callback Javascript调用的回执方法名。
     * @return 发送Ajax请求的语句
     */
    public static function ajaxRequstStatement($url,$dataArray,$method,$response_type=EnumResponseType::XML,$callback=null)
    {
        $result="";
        if (!empty ($callback))
        {        
            $url_base=UtilNet::urlbase();  
            $result=self::loadJsSentence($url_base."common/js/util/xmltojson.js");   
        }
        $result.= "<script type='text/javascript'>"; 
        //<editor-fold defaultstate="collapsed" desc="ExtJs">         
        $result.="Ext.Ajax.request({";                
        $result.= "url:'".$url."',";
        $result.= "method:'".$method."',";
        $result.= "headers:{
                    'response_type':'$response_type'
                },";
        if (isset($dataArray))
        {  
            $result.= "params:";            
            $data=json_encode($dataArray); 
            $result.=$data;
//            $result.= "{";
//            foreach ($dataArray as $key => $value) {
//                $result.=$key.":'".$value."',";                
//            }
//            if (endWith($result, ",")){
//                $result=substr($result, 0, strlen($result)-1);
//            }
//            $result.="}";
            $result.=",";
        }
        if (isset($callback)){ 
            $result.= "success:".$callback;
        }else{
            $result=substr($result, 0,  strlen($result)-1);
        }        
        $result.= "})";
        //</editor-fold>  
        $result.= "</script>";           
        return $result;
    }
    
    /**
     * 生成Javascript的回调函数
     * @param string $local_service_flag 对象名称
     * @param string $callback 回调函数
     * @param enum $response_type 返回的数据类型
     * @return string 回调函数
     */
    public static function callbackForJsFramework($local_service_flag,$response_type=EnumResponseType::XML)
    {   
        $class_name=str_replace("RO","",$local_service_flag);     
        //<editor-fold defaultstate="collapsed" desc="ext-js"> 
        $result="function(response) {
                    //alert(response.responseText);
                    //alert(response.responseXML);
                    if (response.responseText==true){
                      console.log('提交请求执行成功！');
                      return ;
                    }
                    var ol = Ext.get('properties');";
        if (!self::$IsHtmlBody){            
            $result.="
                    Ext.get('object_name').update('$class_name');";
        }
        if (UtilAjax::$ajax_fw_version_default<4){
           $result.="var domhelper= Ext.DomHelper;";
        }else{
           $result.="var domhelper= Ext.core.DomHelper;";
        }            
        if ($response_type==EnumResponseType::XML){
            $result.="
                    var objectJson = xmltoJson(response.responseXML); 
                    for(var item in objectJson) {
                         var value = objectJson[item];
                         if(typeof(value) == 'object') { 
                            for(var subitem in value) {
                                var subvalue = value[subitem];
                                for(var childitem in subvalue) {
                                    if (subitem!='#text'){ 
                                        var childvalue = subvalue[childitem];
                                        domhelper.append(ol, {tag: 'li', html:subitem+':'+childvalue}); 
                                    }
                                }
                            }
                         }
                      }
                    ";
        }
        else if ($response_type==EnumResponseType::JSON){
            $result.="
                    var responseArray=Ext.decode(response.responseText);
                    for(var item in responseArray) {
                        var value = responseArray[item];
                        domhelper.append(ol, {tag: 'li', html:item+':'+value}); 
                    }
                    ";
        }
        $result.="}";
        //</editor-fold> 
        if (!self::$IsHtmlBody){
            echo "<body><h1 id='object_name'></h1><ol id='properties'></ol></body>\r\n"; 
            self::$IsHtmlBody=true;
        } 
        return $result;
    }
}

?>
