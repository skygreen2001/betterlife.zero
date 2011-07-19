<?php    
    require_once("include/service.php");   
    Manager_Communication::init();  
    
    /**
     * 通信--服务端
     */
    class Communication_Server
    {
        const KEYWORD_ID="ID";
        const KEYWORD_ISSUCC="IsSucc";
        const KEYWORD_METHOD="method"; 
        const KEYWORD_ERRORMESSAGE="errorMsg";
        /**
         * 是否Url Mod_Rewrite<br/>
         * Apache需要打开mod_rewrite模块<br/>
         * Nginx需要打开Rewrite Module模块<br/>
         * @link http://wiki.nginx.org/ModuleComparisonMatrix
         * @var bool
         */
        public $Is_Url_Rewrite=false;        
        /**
         * 请求的对象
         * @var string  
         */
        private $object;
        /**
         * 请求的对象名称
         * @var string  
         */
        private $object_name;        
        /**
         * 对象唯一的编号
         * @var string  
         */
        private $id;
        /**
         * 请求的方法,HTTP的标准协议动词【get|post|put|delete】。
         * @var string 
         */
        private $method;
        /**
         *获取提交的数据
         * @var mixed
         */
        private $request_data;
        /**
         * 返回数据类型
         * @var enum 
         */
        public $response_type=EnumResponseType::XML;
        /**
         * 错误信息
         * @var string 
         */
        public $errorMessage;
        
        /**
         * 初始化工作
         */
        public function init()
        {
            if ($this->Is_Url_Rewrite){
                if (isset($_SERVER['PATH_INFO'])){
                    $paths = explode("/",trim($_SERVER['PATH_INFO'],'/'));
                    $this->object_name=ucfirst($paths[0]);
                }else{
                    $this->errorMessage="该请求参数不正确，请检查：http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                    return false;
                }   
            }else{
                if (isset($_GET["service"])){
                    $this->object_name=ucfirst($_GET["service"]);
                }else{
                    $this->errorMessage="该请求参数不正确，请检查：http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                    return false;
                }
            }
            if (isset($_GET["id"])){
                $this->id=$_GET["id"];
            }
            if (isset($_POST["id"])){
                $this->id=$_POST["id"];
            }
            
            $headers=apache_request_headers();
            if(isset($headers['response_type'])){
                $this->response_type=$headers['response_type'];
            }
            
            $this->method=$_SERVER['REQUEST_METHOD'];             
            if (empty($this->method)){
               $this->method=EnumHttpMethod::POST; 
            }
            
            if (isset($this->object_name)){
               $this->object_name=ucfirst($this->object_name);
               $this->object_name=str_replace("RO","",$this->object_name); 
               if (!class_exists($this->object_name)){ 
                   $this->errorMessage="该请求参数不正确，请检查：该请求参数不正确，请检查：http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].$_SERVER['PATH_INFO'];
                   return false;                   
               }  
            }else{
                $this->errorMessage="该请求参数不正确，请检查：该请求参数不正确，请检查：http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].$_SERVER['PATH_INFO'];
                return false;
            }     
            return true;       
        }
        
        /**
         * 执行服务端方法
         */
        public function execute()
        {
            $this->request_data=$this->getPostDatas();
            if ($this->request_data){
                if (is_array($this->request_data)&&(count($this->request_data)>0)){
                   $this->object=UtilObject::array_to_object($this->request_data, $this->object_name);  
                }      
                $this->method=strtoupper($this->method);
                switch ($this->method) {
                    case "POST":
                        if (is_object($this->object)){
                            $this->id=$this->object->save();
                        }
                        if ($this->id>0){
                            echo $this->responseXmlTrue("POST");
                        }else{
                            echo $this->responseXmlFalse("POST");  
                        }
                        break;
                    case "DELETE":
                        $this->id=$this->object->getId();
                        if (is_object($this->object)){
                            $isRight=$this->object->delete();
                            if ($isRight){
                               echo $this->responseXmlTrue("DELETE");
                            }  else {
                               echo $this->responseXmlFalse("DELETE");
                            }
                        }    
                        break;
                    case "PUT":
                        $this->id=$this->object->getId();  
                        if (is_object($this->object)){
                            $isRight=$this->object->update();
                            if ($isRight){
                               echo $this->responseXmlTrue("PUT");
                            }  else {
                               echo $this->responseXmlFalse("PUT");
                            }
                        } 
                        break;
                    case "GET":                          
                        if (is_object($this->object)){
                            if ($this->response_type==EnumResponseType::XML){
                               header('Content-type: application/xml'); 
                               echo $this->object->toXml(false);
                            }else{
                               header('Content-type: application/json');
                               echo $this->object->toJson(false);
                            }
                        }                    
                        break;
                    default:
                        break;
                }
            }
        }
        
        /**
         * 获取提交的数据和参数
         */
        public function getPostDatas()
        {
            $request_data = file_get_contents('php://input');
            if ($request_data&&strlen($request_data)>0){
                if (is_string($request_data)&&startWith(trim($request_data), "<?xml")){      
                   $request_data=UtilArray::xml_to_array($request_data, $this->object_name);
                }else{
                   parse_str($request_data, $request_data);
                }
            }else{      
                $request_data=array();
                if (isset($_SERVER['PATH_INFO'])){
                    $paths=trim($_SERVER['PATH_INFO'],'/');
                    if (contain($paths,"=")){
                        $paths=substr($paths,strrpos($paths, "/")+1);
                        parse_str($paths, $request_data);
                    }                
                }
                $param = array_merge($_POST,$_GET,$request_data);
                $request_data=$param;
            }
            return $request_data;
        }
        
        /**
        * 返回成功
        * @param mixed $isSuccess
        */
        public function responseXmlTrue($operate="")
        {
            $response=array(self::KEYWORD_ISSUCC=>"true");
            if ($this->id){
                $response[self::KEYWORD_ID]=$this->id;
            }            
            if ($this->response_type==EnumResponseType::XML){
                header('Content-type: application/xml');
                if ($this->object_name){
                    $rootNodeName=$this->object_name;
                }else{
                    $rootNodeName="Unknown";
                }            
                $result=UtilArray::array_to_xml($response,$rootNodeName);
                $sxe = new SimpleXMLElement($result);   
                $sxe->addAttribute('operate', $operate);         
                $result=$sxe->asXML();
            }else{
               header('Content-type: application/json');
               $response[self::KEYWORD_METHOD]=$operate;
               $result= json_encode($response);
            }
            return $result;
        }
        
        /**
        * 返回失败
        */
        public function responseXmlFalse($operate="")
        {         
            $response=array(self::KEYWORD_ISSUCC=>"false");
            if ($this->id){
                $response[self::KEYWORD_ID]=$this->id;
            }                
            if ($this->errorMessage){
                $response[self::KEYWORD_ERRORMESSAGE]=$this->errorMessage;
            }     
            if ($this->response_type==EnumResponseType::XML){
                header('Content-type: application/xml');
                if ($this->object_name){
                    $rootNodeName=$this->object_name;
                }else{
                    $rootNodeName="Unknown";
                }            
                $result=UtilArray::array_to_xml($response,$rootNodeName);
                $sxe = new SimpleXMLElement($result);   
                $sxe->addAttribute('operate', $operate);         
                $result=$sxe->asXML();
            }else{
               header('Content-type: application/json');
               $response[self::KEYWORD_METHOD]=$operate;               
               $result=json_encode($response);
            }
            return $result;
        }       
        
    }
        
    //<editor-fold defaultstate="collapsed" desc="主体运行部分">
    $server=new Communication_Server();   
    $isInit=$server->init();
    if ($isInit){
        $server->execute();
    }else{
        $method=$_SERVER['REQUEST_METHOD'];             
        if (empty($method)){
           $method=""; 
        }        
        //LogMe::log($this->errorMessage);
        echo $server->responseXmlFalse($method);
    }
    //$server = new SoapServer($requst_object.'.wsdl', array('soap_version' => SOAP_1_2));  
    //$server->setClass($requst_object);  
    //$server->handle(); 
    //</editor-fold>   
?>
