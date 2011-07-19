<?php

/**
 +---------------------------------<br/>
 * 工具类：Mootools[Javascript Ajax 框架]<br/>
 +---------------------------------
 * @category betterlife
 * @package util.view.ajax
 * @author skygreen
 */
class UtilAjaxMootools extends UtilAjax implements IUtilAjax
{    
    /**
     * 动态加载Mootools:Ajax Javascript Framework库
     * @link http://mootools.net/download
     * @link https://ajax.googleapis.com/ajax/libs/mootools/1.3.2/mootools-yui-compressed.js
     * @param string $version javascript框架的版本号
     * @param ViewObject $viewObject 表示层显示对象,只在Web框架中使用,一般结合loadJsReady使用
     */
    public static function load($version="",$viewObject=null)
    {
        if (self::$IsGoogleApi){
            if ($viewObject)
            {
                self::loadJsReady($viewObject,"https://ajax.googleapis.com/ajax/libs/mootools/$version/mootools-yui-compressed.js");
            }  else {
                self::loadJs("https://ajax.googleapis.com/ajax/libs/mootools/$version/mootools-yui-compressed.js");
            }
        }  else {
            $ajax_root="common/js/ajax/";    
            $group=EnumJsFramework::JS_FW_MOOTOOLS;    
            if ($viewObject)
            {            
                self::loadJsReady($viewObject,$ajax_root.$group."/".$group.".js");
            }  else {
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
            $result= self::loadJsSentence($url_base."common/js/util/xmltojson.js");  
            $result.="<script type='text/javascript'>
                    Element.implement({
                        appendHTML: function(html,where){
                            return this.grab(new Element('text',{'html':html}),where);
                        }
                    });
                    </script>
                ";
        }
        $result.= "<script type='text/javascript'>";  
        //<editor-fold defaultstate="collapsed" desc="Mootools"> 
        //@link http://mootools.net/docs/core/Request/Request
        if ($response_type==EnumResponseType::JSON){                          
            if((is_array($dataArray))&&(count($dataArray)>0))
            {
                $data="{";
                foreach ($dataArray as $key => $value) {
                  $data.=$key.":'".$value."'".",";
                }    
                $data=substr($data, 0, strlen($data)-1);
                $data.="}";
            }    
        }
        else if ($response_type==EnumResponseType::XML) 
        {
            if((is_array($dataArray))&&(count($dataArray)>0))
            {
                $data=http_build_query($dataArray);
            }   
        }
        if ($response_type==EnumResponseType::JSON){                                             
            $result.="var myRequest = new Request.JSON({";
        }
        else if ($response_type==EnumResponseType::XML) 
        {
            $result.="var myRequest = new Request({";
            $result.="method:'".$method."',"; 
        }
        $result.="url:'".$url."',";         
        if (isset ($callback)){
            $result.="onSuccess: $callback,";
        }
        if(Gc::$dev_debug_on){
            if ($response_type==EnumResponseType::JSON){ 
                $result.="onError:function(text, error){
                            console.log('请求失败！ :(。返回信息'+text+'，失败原因：'+error+'。');
                            }\r\n"; 
            } 
            else if ($response_type==EnumResponseType::XML) 
            {
                $result.="
                      onFailure: function(xhr){
                        console.log('请求失败！ :(。失败原因：'+xhr.responseText);
                      }\r\n";
            }
        }else{
            $result=substr($result, 0,  strlen($result)-1);
        }  
        $result.= "});\r\n";
        $result.= "myRequest.setHeader('response_type', '$response_type');\r\n"; 
        $result.= "myRequest.setHeader('request_method', '$method');\r\n"; 
        if ($response_type==EnumResponseType::JSON){        
            $result.="myRequest.get($data);";
        }
        else if ($response_type==EnumResponseType::XML) 
        {
            $result.=" myRequest.send('$data');";
        }
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
        //<editor-fold defaultstate="collapsed" desc="Mootools"> 
        $result="function(response) {
                    if (response==true){
                      console.log('提交请求执行成功！');
                      return ;
                    }
                    var ol = $(document.body).getElement('ol');
                    var h1 = $(document.body).getElement('h1');";
        if (!self::$IsHtmlBody){            
            $result.="            
                    h1.appendText('$class_name');";
        }
        if ($response_type==EnumResponseType::JSON){
           $result.="
                    for(var item in response) {
                        var value = response[item];
                        ol.appendHTML('<li>'+item+':'+value+'</li>');
                    }
                    ";
        }
        else if ($response_type==EnumResponseType::XML){
            $result.="
                      var responseXml=createXmlDom(response);
                      var objectJson = xmltoJson(responseXml);  
                      //var dumpt=dump(objectJson);
                      //alert(dumpt);
                      //$(document.body).appendText(dumpt);
                      for(var item in objectJson) {
                         var value = objectJson[item];
                         if(typeof(value) == 'object') { 
                            for(var subitem in value) {
                                var subvalue = value[subitem];
                                for(var childitem in subvalue) {
                                    if (subitem!='#text'){   
                                        var childvalue = subvalue[childitem];
                                        ol.appendHTML('<li>'+subitem+':'+childvalue+'</li>');
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
