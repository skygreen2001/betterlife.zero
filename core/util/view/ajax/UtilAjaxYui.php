<?php

/**
 +---------------------------------<br/>
 * 工具类：YUI[Javascript Ajax 框架]<br/>
 * Yahoo! User Interface Library (YUI)<br/>
 +---------------------------------
 * @category betterlife
 * @package util.view.ajax
 * @author skygreen
 */
class UtilAjaxYui extends UtilAjax implements IUtilAjax
{
    /**
     * 动态加载YUI:Ajax Javascript Framework库
     * @link http://developer.yahoo.com/yui/
     * @link https://ajax.googleapis.com/ajax/libs/yui/3.3.0/build/yui/yui-min.js
     * @param string $version javascript框架的版本号
     * @param ViewObject $viewObject 表示层显示对象,只在Web框架中使用,一般结合loadJsReady使用
     */
    public static function load($version="",$viewObject=null)
    {
        if (self::$IsGoogleApi){
            if ($viewObject)
            {            
                self::loadJsReady($viewObject,"https://ajax.googleapis.com/ajax/libs/yui/$version/build/yui/yui-min.js");
            }else{
                self::loadJs("https://ajax.googleapis.com/ajax/libs/yui/$version/build/yui/yui-min.js");                
            }
        }else{
            self::loadAjaxJs(EnumJsFramework::JS_FW_YUI,$version,$viewObject);
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
        //<editor-fold defaultstate="collapsed" desc="YUI"> 
                     
        if((is_array($dataArray))&&(count($dataArray)>0))
        {
            $data=http_build_query($dataArray);
        }  
        $result.="YUI().use('io-base', function(Y) {
                    var cfg,request;
                    cfg = {
                        method: '$method',
                        data: '$data',
                        headers:{
                            'response_type':'$response_type'
                        }
                    };";
        if (isset($callback)){
             $result.=$callback;
             $result.="
                    Y.on('io:complete',onComplete,Y,true);";  
        }
        $result.="  
                    request = Y.io('$url', cfg);";
        $result.="
                  });";   
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
        //<editor-fold defaultstate="collapsed" desc="YUI">
        $result="
                  function onComplete(id, response, args){
                    if (response.responseText==true){
                      console.log('提交请求执行成功！');
                      return ;
                    }";
        if (!self::$IsHtmlBody){            
            $result.="      
                    YUI().use('node', function (Y) {    
                        Y.one('#object_name').append('$class_name');
                    })
            ";
        }
        if ($response_type==EnumResponseType::JSON){
            $result.="
                    var data;
                    YUI().use('json-parse', function (Y) {
                        try {
                          data = Y.JSON.parse(response.responseText);
                        }
                        catch (e) {
                            console.log('返回的Json数据语法格式错误！');
                        }
                    })     
                    YUI().use('node', function (Y) {    
                        for(var item in data) {
                            var value = data[item];
                            Y.one('#properties').append('<li>'+item+':'+value+'</li>');
                        }
                    })   
                    ";                        
        }
        else if ($response_type==EnumResponseType::XML){                      
            $result.="
                  var objectJson = xmltoJson(response.responseXML);    
                  YUI().use('node', function (Y) {
                      for(var item in objectJson) {
                             var value = objectJson[item];
                             if(typeof(value) == 'object') { 
                                for(var subitem in value) {
                                    var subvalue = value[subitem];
                                    for(var childitem in subvalue) {
                                        if (subitem!='#text'){    
                                            var childvalue = subvalue[childitem];
                                            Y.one('#properties').append('<li>'+subitem+':'+childvalue+'</li>');
                                        }
                                    }
                                }
                             }
                          }
                  })
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
