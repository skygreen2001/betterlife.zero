<?php

/**
 +---------------------------------<br/>
 * 工具类：Prototype[Javascript Ajax 框架]<br/>
 +---------------------------------
 * @category betterlife
 * @package util.view.ajax
 * @author skygreen
 */
class UtilAjaxPrototype extends UtilAjax implements IUtilAjax
{    
    /**
     * 动态加载Prototype:Ajax Javascript Framework库
     * @link http://api.prototypejs.org/Prototype/
     * @link https://ajax.googleapis.com/ajax/libs/prototype/1.7.0.0/prototype.js
     * @param string $version javascript框架的版本号
     * @param ViewObject $viewObject 表示层显示对象,只在Web框架中使用,一般结合loadJsReady使用
     */
    public static function load($version="",$viewObject=null)
    {
        if (self::$IsGoogleApi)
        {
            if ($viewObject)
            {
                self::loadJsReady($viewObject,"https://ajax.googleapis.com/ajax/libs/prototype/$version/prototype.js");
            }else{
                self::loadJs("https://ajax.googleapis.com/ajax/libs/prototype/$version/prototype.js");                
            }
        }else{        
            $ajax_root="common/js/ajax/";    
            $group=EnumJsFramework::JS_FW_PROTOTYPE;   
            if ($viewObject)
            {
                self::loadJsReady($viewObject,$ajax_root.$group."/".$group.".js");
            }else{
                self::loadJs($ajax_root.$group."/".$group.".js");                
            }
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
        //<editor-fold defaultstate="collapsed" desc="ProtoType">    
         
        if((is_array($dataArray))&&(count($dataArray)>0))
        {
            $data=http_build_query($dataArray);
        }  
        $result.="new Ajax.Request('$url',{";  
        $result.="method: '".$method."',";
        $result.="parameters: '$data',";
        $result.="requestHeaders:{
            'response_type':'$response_type'
        },";
        if (isset($callback)){ 
            $result.= "onSuccess:".$callback.",";
        }
        if (Gc::$dev_debug_on){        
            $result.="onException: function(transport,e){
                 console.log('请求失败！ :(||||'+e.name+':'+e.message);
            },";            
            $result.="
                  onFailure: function(request){
                    console.log('请求失败！ :(');
                  }\r\n";          
        }else{            
            $result=substr($result, 0,  strlen($result)-1);
        }
        $result.= "});";
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
        //<editor-fold defaultstate="collapsed" desc="Prototype"> 
        $result="function(response) {";
        $result.="   
                    var ol = $('properties');
                    var h1 = $('object_name');";
        if (!self::$IsHtmlBody){            
            $result.="            
                    h1.insert('$class_name');";                    
        }
        if ($response_type==EnumResponseType::JSON){
            $result.="
                    var responseJson=response.responseJSON;
                    for(var item in responseJson) {
                        var value = responseJson[item];
                        ol.insert({bottom:'<li>'+item+':'+value+'</li>'});
                    }
                    ";                        
        }
        else if ($response_type==EnumResponseType::XML){
            $result.="
                  var responseXml=response.responseXML;//当返回header的content-type:application/xml;
                  //var responseXml=createXmlDom(response.responseText);
                  var objectJson = xmltoJson(responseXml);
                  for(var item in objectJson) {
                         var value = objectJson[item];
                         if(typeof(value) == 'object') { 
                            for(var subitem in value) {
                                var subvalue = value[subitem];
                                for(var childitem in subvalue) {
                                  if (subitem!='#text'){
                                    var childvalue = subvalue[childitem];
                                    ol.insert({bottom:'<li>'+subitem+':'+childvalue+'</li>'});
                                  }
                                }
                            }
                         }
                      }
                    ";
        };
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
