<?php
//加载初始化设置
class_exists("Service")||require(dirname(__FILE__)."/../../../../init.php");
Manager_Communication::init();
/**
 +---------------------------------<br/>
 * 提供远程数据服务。<br/>
 +---------------------------------
 * @category betterlife
 * @package web.back.admin.services
 * @author skygreen
 */
class Service_Communication extends Service
{
    /**
     * 第三方进行通信的服务器地址
     * @var string 
     */    
    public static $remote_server;  
    /**
     * 第三方进行通信的服务器索引Index文件地址。
     * @var string
     */
    public static $index_file;
    /**
     * 请求的方法,HTTP的标准协议动词【get|post|put|delete】。
     * @var string 
     */
    private $method;
    /**
     * 返回数据类型
     * @var enum 
     */
    public $response_type=EnumResponseType::XML;
    
    /**
     * 默认方式：异步通信执行的结果
     * @param string $str_object
     * @param mixed $request_data 
     */
    public function common($str_object,$request_data)
    {
        $this->execute($str_object,$request_data);
     }
    
    /**
     *
     * @param type $request_data
     * @param array|string $str_object
     * @return type 核心应用程序的封装。
     */
    private function execute($str_object,$request_data)
    {
        if (isset($_SERVER['HTTP_RESPONSE_TYPE'])){
            $this->response_type=$_SERVER['HTTP_RESPONSE_TYPE'];
        }
        self::$remote_server=RemoteObject::$server_addr;    
        self::$index_file=RemoteObject::$index_file; 
        if (Gc::$dev_debug_on){
//        $cet_ping = ping_url(self::$remote_server.$str_object);
//        if(!strstr($cet_ping,'HTTP/1.1 200 OK')){
//            return;
//        }    
        }
        $this->method=$_SERVER['REQUEST_METHOD'];
        if (isset($_SERVER['HTTP_REQUEST_METHOD'])){
            $this->method=$_SERVER['HTTP_REQUEST_METHOD'];
        }        
        if ($this->response_type==EnumResponseType::XML){
           header('Content-type: application/xml'); 
        }else{
           header('Content-type: application/json');
        }       
        if (contain($str_object, __CLASS__)){
            $str_object=str_replace( __CLASS__."::", "", $str_object);
        }              
        if (strtoupper($this->method)==EnumHttpMethod::GET){
            echo Manager_Communication::newInstance()->currentComm()->sendRequest(self::$remote_server.$str_object,$request_data,$this->method,$this->response_type);
        }else{
            $str_objectRO=$str_object."RO";
            //当Ajax框架为Prototype或者Mootools时，会通过param:_method提交原始的request method。
            if (isset($_POST["_method"])){
                $this->method=$_POST["_method"];
            } 
            if (isset($request_data["data"])){
                $data=$request_data["data"];
                $request_data=UtilArray::String2Array($data);
                $data=UtilArray::array_to_xml($request_data);
            }else{ 
                if (class_exists($str_objectRO)){
                    $objectRO=new $str_objectRO();
                }
                if ($objectRO){ 
                    $Is_Url_Rewrite=$objectRO->Is_Url_Rewrite;
                }
                unset($request_data["service"]);
                $objectXml=UtilArray::array_to_xml($request_data,$objectRO->classname());  
                //$objectXml=$objectRO->toXml(); 
                
                $objectXml=str_replace($str_objectRO,$str_object,$objectXml); 
                $sxe = new SimpleXMLElement($objectXml);
                $attribute_href=self::$remote_server.$str_object.".xml";     
                $sxe->addAttribute('href', $attribute_href);         
                $data=$sxe->asXML(); 
            }
            if ($Is_Url_Rewrite){ 
                echo Manager_Communication::newInstance()->currentComm()->sendRequest(self::$remote_server.$str_object,$data,$this->method,$this->response_type);
            }else{
                echo Manager_Communication::newInstance()->currentComm()->sendRequest(self::$remote_server.self::$index_file.$str_object,$data,$this->method,$this->response_type);
            }
        }
    }
    
    /**
     * 远程服务对象：用户-User
     * @param type $request_data 
     */
    public function User($request_data)
    { 
        $this->execute(__METHOD__,$request_data);        
    }
}

if (isset($_GET["service"])){
    $service=$_GET["service"];
    $service=str_replace("RO","",$service);  
    $request_data = file_get_contents('php://input');
    $request_data_array=array();
    if ($request_data&&strlen($request_data)>0){ 
        parse_str($request_data, $request_data_array);
    }
    $request_data=array_merge($_GET,$_POST,$request_data_array);
    $service_communication= new Service_Communication();
    switch ($service) {
        case "User":
             @$service_communication->$service($request_data);
            break;
        default:
            if (!empty($service)){
                @$service_communication->common($service,$request_data);
            }
            break;
    }

}
?>
