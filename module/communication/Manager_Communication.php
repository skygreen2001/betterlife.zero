<?php
//<editor-fold defaultstate="collapsed" desc="枚举类型">
/**
 * 实现通信的方案类型
 */
class EnumCommunicationType extends Enum{
    /**
     * 通过cUrl实现通信
     */
    const HTTP_CLIENT_CURL="Comm_Curl";
    /**
     * 通过Http Client实现通信
     */
    const HTTP_CLIENT_HTTPCLIENT="Comm_HttpClient";
    /**
     * 通过Http Client另一种实现——实现通信
     */
    const HTTP_CLIENT_HTTPCLIENT_ANOTHER="Comm_Another_HttpClient";
    /**
     * 通过Nusoap实现通信
     */
    const WEBSERVICE_NUSOAP="Comm_Nusoap";
    /**
     * 通过Php自带的Soap Extension实现通信
     */
    const WEBSERVICE_PHPSOAP="WEBSERVICE_PHPSOAP";
}

/**
 * 请求响应的数据类型
 */
class EnumResponseType extends Enum{
    /**
     * json
     */
    const JSON="json";
    /**
     * xml
     */
    const XML="xml";
    /**
     * html
     */
    const HTML="html";
    /**
     * jsonp
     */
    const JSONP="jsonp";
    /**
     * text
     */
    const TEXT="text";
    /**
     * serialized
     */
    const SERIALIZED="serialized";
}

/**
 * HTTP的标准协议动词
 */
class EnumHttpMethod extends Enum{
    /**
     * 即常见的url Get方式。
     */
    const GET="GET";
    /**
     * 即常见的url Post方式。
     */
    const POST="POST";
    /**
     * PUT Restful中更新数据
     */
    const PUT="PUT";
    /**
     * DELETE Restful中删除数据
     */
    const DELETE="DELETE";
    /**
     * 暂未实现启用
     */
    const OPTIONS="OPTIONS";
}
//</editor-fold>

/**
 +---------------------------------<br/>
 * 负责通信的管理类，由它最终决定采用何种策略进行通信<br/>
 +---------------------------------
 * @category betterlife
 * @package module.communication
 * @author skygreen
 */
class Manager_Communication extends Manager{
    /**
     * 当前唯一实例化的通信管理类。
     * @var Manager_Communication 
     */
    public static $manager_communication;
    /**
     * 当前负责通信的类
     */
    public static $comm;
    /**
     * 默认的通信方案
     */    
    public static $comm_default=EnumCommunicationType::HTTP_CLIENT_CURL;
    /**
     * 用于异步通信的本地地址。<br/>
     * 一般用于javascript ajax 发送请求给后台，由后台发送请求给指定的通信url地址。<br/>
     * 它的好处是对当前运行的应用程序不会造成阻塞。<br/>
     * 其中go是跳转的关键字，对应Router::VAR_DISPATCH;如果该变量修改后，该关键字也应修改。
     */
    const communication_urlbase="home/admin/src/services/Service_Communication.php?service=";     
        
    /**
     * 构造器
     */
    private function __construct() 
    {
    }
    
    /**
     * 单例化
     */
    public static function newInstance()
    {
        if (self::$manager_communication==null) {
            self::$manager_communication=new Manager_Communication();
        }
        return self::$manager_communication;
    }
    
    /**
     * 初始化方能加载枚举类型。
     */
    public static function init(){}
    
    /**
     * 根据通信实现方式标识符获取通信实现方式。
     * @param enum $commType 通信实现方式标识符
     * @return 通信实现方式 
     */
    private static function getComm($commType=null)
    {
        if (($commType==null)||(empty($commType))){
            $commType=self::$comm_default;
        }
        $result=$commType;
        return $result;
    }
    
    /**
     * 获取当前通信的类。
     * @param enum $commType 通信实现方式标识符
     * @return 自己
     */    
    public function currentComm($commType=null)
    {
        if(self::$comm==null){
            self::$comm=self::getComm($commType);
        }else{
           if($commType!=null){
              self::$comm=self::getComm($commType);
           } 
        }
        return $this;
    }    
    
     /**
     * 异步通信目标传送或者获取数据
     * @param string $url。目标url地址<br/>
     * @param array $dataArray 传送的数据。
     * @param enum $response_type 返回的数据类型
     * @param array $method HTTP的标准协议动词【get|post|put|delete】。
     * @param string $callback Javascript调用的回执方法名。
     */
    public function sendRequestAsync($url,$dataArray,$method=EnumHttpMethod::POST,$response_type=EnumResponseType::XML,$callback=null)
    {  
        $result=UtilJavascript::ajaxRequstStatement($url,$dataArray,$method,$response_type,$callback);
        return $result;
    }
    
    /**
     * 异步通信第三方传送或者获取数据
     * @param string $local_addr_flag。
     * 它是具体执行跳转的本地地址，一般对应Router::VAR_DISPATCH-go后面的三段字符串；<br/>
     * 默认是:admin.communication.[方法名]
     * @param array $dataArray 传送的数据。
     * @param array $method HTTP的标准协议动词【get|post|put|delete】。
     * @param enum $response_type 返回的数据类型
     * @param string $callback Javascript调用的回执方法名。
     */
    public function sendRequestAsync_local($local_service_flag,$dataArray,$method=EnumHttpMethod::POST,$response_type=EnumResponseType::XML,$callback=null)
    {
        $loadJsLibrary=UtilAjax::name().ucfirst(UtilAjax::$ajax_fw_name_default);
        if (Gc::$dev_debug_on&&empty($callback)){        
            $callback=call_user_func_array($loadJsLibrary."::callbackForJsFramework",array($local_service_flag,$response_type));        
        }
        $url_base=UtilNet::urlbase();
        $result=self::sendRequestAsync($url_base.self::communication_urlbase.$local_service_flag, $dataArray,$method,$response_type,$callback);
        return $result; 
  
  }

    /**
     * 发送请求到第三方。
     * @param string $url 通信的Url地址。
     * @param array $data 传送的数据。
     * @param array $method HTTP的标准协议动词【get|post|put|delete】。
     * @param enum $response_type 返回的数据类型
     * @param array $headers 请求头信息。
     * @param string $callback Javascript调用的回执方法名。
     * @return 当$method=【post|put|delete】时，返回bool;true:传送成功，false:传送失败。<br/>
     * 当$method=get时，根据返回类型要求返回具体的内容。
     */
    public function sendRequest($url,$data=null,$method=EnumHttpMethod::POST,$response_type=EnumResponseType::XML,$headers=null, $callback=null)
    {
        if (self::$comm==null){
            self::$comm=self::getComm();
        }
        $comm=self::$comm;
        $method=strtoupper($method);
        switch ($method){
            case EnumHttpMethod::POST:
                return call_user_func($comm."::post", $url, $data, $headers, $callback,$response_type);            
            case EnumHttpMethod::GET:
                //<editor-fold defaultstate="collapsed" desc="data转换成QueryString">
                if ( $data )
                {
                    if (is_string($data)&&startWith(trim($data), "<?xml")){      
                       $data=UtilArray::xml_to_array($data);
                    }                    
                    if ( is_array( $data ) )
                    {
                        $data = http_build_query( $data );     
                        if (contain($url, "?")){
                           $url.="&"; 
                        }else{
                           $url.="?"; 
                        }
                        $url.=$data;
                    }
                }
                //</editor-fold>                   
                return call_user_func($comm."::get",$url, $headers,$response_type);
            case EnumHttpMethod::PUT:
                return call_user_func($comm."::put",$url, $data, $headers, $callback,$response_type);
                break;
            case EnumHttpMethod::DELETE:
                return call_user_func($comm."::delete",$url, $data, $headers,$callback,$response_type);
                break;
        }
    }         
}
?>
