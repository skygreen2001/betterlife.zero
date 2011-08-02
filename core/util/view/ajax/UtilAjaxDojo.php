<?php

/**
 +---------------------------------<br/>
 * 工具类：Dojo[Javascript Ajax 框架]<br/>
 +---------------------------------
 * @category betterlife
 * @package util.view.ajax
 * @author skygreen
 */
class UtilAjaxDojo extends UtilAjax implements IUtilAjax
{    
    /**
     * 动态加载Dojo:Ajax Dojo Framework库
     * @link http://dojotoolkit.org/
     * @link https://ajax.googleapis.com/ajax/libs/dojo/1.6.0/dojo/dojo.xd.js
     * @param string $version javascript框架的版本号
     * @param ViewObject $viewObject 表示层显示对象,只在Web框架中使用,一般结合loadJsReady使用
     */
    public static function load($version="",$viewObject=null)
    {
        if (self::$IsGoogleApi)
        {
            if ($viewObject)
            {
                self::loadJsReady($viewObject,"https://ajax.googleapis.com/ajax/libs/dojo/$version/dojo/dojo.xd.js");
            }else{
                self::loadJs("https://ajax.googleapis.com/ajax/libs/dojo/$version/dojo/dojo.xd.js");
            } 
        }else{
            self::loadAjaxJs(EnumJsFramework::JS_FW_DOJO,$version,$viewObject); 
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
            $result=self::loadJsSentence("common/js/util/xmltojson.js");  
        }
        $result.= "<script type='text/javascript'>";
        //<editor-fold defaultstate="collapsed" desc="dojo">       
        if((is_array($dataArray))&&(count($dataArray)>0))
        {
            $data=json_encode($dataArray);
//            $data="{";
//            foreach ($dataArray as $key => $value) {
//              $data.=$key.":'".$value."'".",";
//            }    
//            $data=substr($data, 0, strlen($data)-1);
//            $data.="}";
        }          
        $result.="
            //Deferred对象允许用同步调用的写法写异步调用
            var deferredResult = ";
        $method=strtoupper($method);
        switch ($method) {
            case EnumHttpMethod::POST:
                $result.="
                    dojo.xhrPost({";
                break;;
            case EnumHttpMethod::GET:
                $result.="
                    dojo.xhrGet({";
                break;;
            case EnumHttpMethod::PUT:
                $result.="
                    dojo.xhrPut({";
                break;;
            case EnumHttpMethod::DELETE:
                $result.="
                    dojo.xhrDelete({";
                break;;
        }        
        $result.="
                url: '$url',
                content: $data, 
                handleAs:'$response_type', //得到的response将被认为是JSON，并自动转为object
                headers:{
                    'response_type':'$response_type'
                }
            });";        
        if (isset($callback)){ 
            $result.="
                //当响应结果可用时再调用回调函数
                deferredResult.then($callback);                           
            ";   
        }
        
        $result.="
            error: function(error, ioargs) {
                var message = '';
                switch (ioargs.xhr.status) {
                case 404:
                    message = '无法找到请求页面。';
                    break;
                case 500:
                    message = '服务器端报告一个错误。';
                    break;
                case 407:
                    message = '需要代理认证。';
                    break;
                default:
                    message = '未知的错误';
                }
                alert(message+':'+error);
               console.log(message+':'+error);
               deferredResult.reject(error);  
            };";

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
        //<editor-fold defaultstate="collapsed" desc="dojo">
        $result="function(response) {
                    if (response==null){
                      console.log('请求失败！检查头信息是否是application/xml或者application/json :(');
                      return ;
                    }";
        if (!self::$IsHtmlBody){           
            $result.="            
                    dojo.byId('object_name').innerHTML='$class_name';";
        }
        if ($response_type==EnumResponseType::JSON){
            $result.="
                      //var responseJson = dojo.fromJson(response);
                      var responseJson =response;
                      for(var item in responseJson) {
                         var value = responseJson[item];
                         dojo.place('<li>'+item+':'+value+'</li>', 'properties','last');
                      }
                  }";
        }
        else if ($response_type==EnumResponseType::XML){
            $result.="                                  
                      var objectJson = xmltoJson(response);              
                      for(var item in objectJson) {
                         var value = objectJson[item];
                         if(typeof(value) == 'object') { 
                            for(var subitem in value) {
                               var subvalue = value[subitem];
                               for(var childitem in subvalue) {
                                  var childvalue = subvalue[childitem];
                                  if (subitem!='#text'){
                                      dojo.place('<li>'+subitem+':'+childvalue+'</li>', 'properties','last');
                                  }
                               }
                            }
                         }
                      }
                      return response; //必须返回response
                  }";
        }
        //</editor-fold>
        if (!self::$IsHtmlBody){   
            echo "<body><h1 id='object_name'></h1><ol id='properties'></ol></body>\r\n"; 
            self::$IsHtmlBody=true;
        }
        return $result;
    }
}

?>
