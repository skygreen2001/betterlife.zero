<?php
/**
 +---------------------------------<br/>
 * 工具类：Jquery[Javascript Ajax 框架]<br/>
 +---------------------------------
 * @category betterlife
 * @package util.view.ajax
 * @author skygreen
 */
class UtilAjaxJquery extends UtilAjax implements IUtilAjax
{
    /**
     * 动态加载Jquery:Ajax Javascript Framework库
     * @link http://jquery.com/
     * @link https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js
     * @param string $version javascript框架的版本号
     * @param ViewObject $viewObject 表示层显示对象,只在Web框架中使用,一般结合loadJsReady使用
     */
    public static function load($version="",$viewObject=null)
    {     
        if (self::$IsGoogleApi){
            if ($viewObject)
            {
                self::loadJsReady($viewObject,"https://ajax.googleapis.com/ajax/libs/jquery/$version/jquery.min.js");
            }else{
                self::loadJs("https://ajax.googleapis.com/ajax/libs/jquery/$version/jquery.min.js");
            }
        }else{
            $ajax_root="common/js/ajax/";    
            $group=EnumJsFramework::JS_FW_JQUERY;
            if ($viewObject)
            {
                self::loadJsReady($viewObject,$ajax_root.$group."/".$group."-".$version.".min.js"); 
            }else{
                self::loadJs($ajax_root.$group."/".$group."-".$version.".min.js"); 
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
        $result.= "<script type='text/javascript'>";  
        //<editor-fold defaultstate="collapsed" desc="JQuery">
        //@link http://api.jquery.com/jQuery.ajax/
        if((is_array($dataArray))&&(count($dataArray)>0))
        {
            $data="{";
            foreach ($dataArray as $key => $value) {
              $data.=$key.":'".$value."'".",";
            }    
            $data=substr($data, 0, strlen($data)-1);
            $data.="}";
        }

        $result.= "$.ajax({";
        $result.= "url:'".$url."',";
        $result.= "type:'".$method."',";
        $result.= "dataType:'".$response_type."',";
        $result.= "beforeSend  : function (XMLHttpRequest) {
	        XMLHttpRequest.setRequestHeader('response_type','$response_type');
	    },";        
        if (isset($data)){        
            $result.= "data:".$data.",";
        }
        if (isset($callback)){ 
            $result.= "success:".$callback.",";
        }     
        if (Gc::$dev_debug_on){
            $result.= "error: function(xhr,status,errMsg){
                      console.log(status,':',errMsg);
                  },";        
            $result.= "statusCode: {
                404: function() {
                  alert('无法找到该页面！');
                }
              }";
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
        //<editor-fold defaultstate="collapsed" desc="JQuery"> 
        $result="
                function(data) {";
        if (!self::$IsHtmlBody){            
            $result.="
                        $('h1').append('$class_name');";
        }
        if ($response_type==EnumResponseType::XML){
            $result.="   
                        $(data).find('$class_name').each(function(i){
                            if ($(this).children()){                 
                                $(this).children().each(function(i){
                                    var name=(this).nodeName; 
                                    var text=$(this).text();  
                                    if (text){                         
                                        $('ol').append('<li>'+name+':'+text+'</li>');
                                    }
                                });                                       
                            }                                    
                        });";
        }
        else if ($response_type==EnumResponseType::JSON){
            $result.=" 
                        $.each(data,function(key, val) {
                            $('ol').append('<li>'+key+':'+val+'</li>');
                        });
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
