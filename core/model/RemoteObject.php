<?php
Manager_Communication::init();
/**
  +---------------------------------<br/>
 * 远程对象的父类，主要用于客户端向服务端发送请求<br/>
  +---------------------------------
 * @category betterlife
 * @package core.model
 * @author skygreen
 */
class RemoteObject extends Object
{   
    /**
     * 第三方进行通信的服务器地址
     * @var string 
     */
    public static $server_addr="http://localhost/betterlife/data/exchange/betterlife/server/";
    /**
     * 第三方进行通信的服务器索引Index文件地址。
     * @var string
     */
    public static $index_file="index.php?service=";       
    /**
     * 客户端应用名称标识，用以服务端鉴定数据来源
     * @var string
     */
    const APP_FLAG="mall";
    /**
     * 客户端应用名称标识，用以服务端鉴定数据来源
     * @var string
    */
    protected $app_name=self::APP_FLAG; 
    /**
     * 是否同步进行远程对象通信操作。同步会对当前运行的程序造成阻塞
     * @var bool
     */
    public $IsSync=false;
    /**
     * 是否Url Mod_Rewrite<br/>
     * Apache需要打开mod_rewrite模块<br/>
     * Nginx需要打开Rewrite Module模块<br/>
     * @link http://wiki.nginx.org/ModuleComparisonMatrix
     * @var bool
     */
    public $Is_Url_Rewrite=false;
    /**
     * 响应数据类型
     * @var enum 
     */
    public $response_type=EnumResponseType::XML;
    
    //<editor-fold defaultstate="collapsed" desc="远程对象通信操作">
    /**
     * 远程获取数据
     * @todo
     */
    public function get()
    {
        if ($this->IsSync){
            return $this->sendRequest(EnumHttpMethod::GET,$this->response_type);
        }else{
            return $this->sendRequestAsyncLocal(EnumHttpMethod::GET,$this->response_type);
        }
    }
    /**
     * 远程提交新增数据
     * 
     */
    public function post()
    {
        if ($this->IsSync){
            return $this->sendRequest(EnumHttpMethod::POST,$this->response_type);
        }else{
            return $this->sendRequestAsyncLocal(EnumHttpMethod::POST,$this->response_type);
        }
    }
    /**
     * 远程提交更新数据
     * 
     */
    public function put()
    {
        if ($this->IsSync){
            return $this->sendRequest(EnumHttpMethod::PUT,$this->response_type);
        }else{
            return $this->sendRequestAsyncLocal(EnumHttpMethod::PUT,$this->response_type);
        }
    }
    /**
     * 远程删除数据
     */
    public function delete()
    {
        if ($this->IsSync){
            return $this->sendRequest(EnumHttpMethod::DELETE,$this->response_type);
        }else{
            return $this->sendRequestAsyncLocal(EnumHttpMethod::DELETE,$this->response_type);
        }
    }
    //</editor-fold> 

    //<editor-fold defaultstate="collapsed" desc="与第三方进行通信">
    /**
     * 直接发送请求<br/>
     * @param enum $response_type 返回的数据类型
     * @param array $method HTTP的标准协议动词【get|post|put|delete】。
     */
    public function sendRequest($method=EnumHttpMethod::POST,$response_type=EnumResponseType::XML)
    {
        if ($response_type==EnumResponseType::XML){
            header('Content-type: application/xml');   
        }else{
            header('Content-type: application/json');            
        }
        $Is_Url_Rewrite=$this->Is_Url_Rewrite;
        unset ($this->response_type);
        unset ($this->IsSync);
        unset ($this->Is_Url_Rewrite);
        $data=UtilObject::object_to_array($this);
        if (!Gc::$dev_debug_on){
            foreach ($data as $key=>$value){
                if (is_array($value)){
                    foreach ($value as $subkey=>$subvalue){
                        if (is_object($subvalue)){
                            $data[$key][$subkey]=UtilObject::object_to_array($subvalue);
                        }                      
                    }
                }
            }    
        }
        if ($Is_Url_Rewrite){
            return Manager_Communication::newInstance()->currentComm()->sendRequest(
                   self::$server_addr.$this->classname(),$data,$method,$response_type);
        }else{
            return Manager_Communication::newInstance()->currentComm()->sendRequest(
                   self::$server_addr.self::$index_file.$this->classname(),$data,$method,$response_type);
        }
    }
    
    /**
     * 异步发送请求<br/>
     * @param enum $response_type 返回的数据类型
     * @param array $method HTTP的标准协议动词【get|post|put|delete】。
     */
    public function sendRequestAsyncLocal($method=EnumHttpMethod::POST,$response_type=EnumResponseType::XML)
    {
        unset ($this->response_type);
        unset ($this->IsSync);        
        $data=UtilObject::object_to_array($this);
        foreach ($data as $key=>$value){
            if (is_array($value)){
                foreach ($value as $subkey=>$subvalue){
                    if (is_object($subvalue)){
                        $data[$key][$subkey]=UtilObject::object_to_array($subvalue);
                    }                      
                }
            }
        }    
        if (!Gc::$dev_debug_on){            
            $data=UtilArray::Array2String($data);
            $data=array("data"=>$data);  
        }  
        $result= Manager_Communication::newInstance()->currentComm()->sendRequestAsync_local($this->classname(),$data,$method,$response_type);   
        return $result;
    }    
    //</editor-fold>    
    
    //<editor-fold defaultstate="collapsed" desc="数据类型转换">
    /**
     * 将数据对象转换成xml
     * @param $filterArray 需要过滤不生成的对象的field<br/>
     * 示例：$filterArray=array("id","commitTime");
     * @param $isAll 是否对象所有的field都要生成，包括没有内容或者内容为空的field
     * @return xml内容
     */
    public function toXml($isAll=true,$filterArray=null)
    {
       $object=clone $this;
       return UtilObject::object_to_xml($object,$filterArray,$isAll);
    }
    
    /**
    * 将数据对象转换成Json类型格式
     * @param $isAll 是否对象所有的field都要生成，包括没有内容或者内容为空的field
    * @return Json格式的数据格式的字符串。
    */
    public function toJson($isAll=false)
    {
       $object_arr=UtilObject::object_to_array($this,$isAll);
       if ($isAll){
           foreach($object_arr as $key=>$value){
               if ($object_arr[$key]==null){
                   $object_arr[$key]="";
               }
           }
       }
       return json_encode($object_arr);
    }
    
    /**
     * 将数据对象转换成Array
     * @param $isAll 是否对象所有的field都要生成，包括没有内容或者内容为空的field
     * @return 数组
     */
    public function toArray($isAll=true)
    {  
       return UtilObject::object_to_array($this,$isAll);
    }
    //</editor-fold>
}

?>
