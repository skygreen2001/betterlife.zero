<?php

/**
 +---------------------------------<br/>
 * 配置类：Service<br/>
 +---------------------------------
 * @category betterlife
 * @package web.back.admin.services
 * @author skygreen
 */
class Config_Service extends ConfigBB
{
    /**
     * 主要提供给Ext Direct的配置文件描述
     */
    const CONFIG_EXT_DIRECT_FILE="service.config.xml";
    
    /**
     * 返回服务配置<br/>
     * 主要提供给Ext Direct的配置。
     * @return array 服务配置
     */
    public static function serviceConfig()
    {
        $configArr=UtilXMLLib::xmltoArray(dirname(__FILE__).DIRECTORY_SEPARATOR.self::CONFIG_EXT_DIRECT_FILE);        
        //$result=UtilXmlSimple::fileXmlToArray(dirname(__FILE__).DIRECTORY_SEPARATOR.self::CONFIG_EXT_DIRECT_FILE);
        $result=self::parseExtDirectConfigArrary($configArr);
        return $result;
    }
    
    /**
     * 将xml转换后的数组转换成 Ext Direct Remote通信所需的配置数组。
     * @param array $configArr 将xml转换后的数组
     * @return array Ext Direct Remote通信所需的配置数组
     */
    private static function parseExtDirectConfigArrary($configArr)
    {
        $result=null;
        $services=array();

        if (is_array($configArr)){        
            foreach ($configArr as $service=>$serviceGroup){
                if (is_array($serviceGroup)){
                    if (!array_key_exists("service attr",$serviceGroup)){
                        $serviceGroup=array_values($serviceGroup);
                        $serviceGroup=$serviceGroup[0];
                    }
                }
                if (is_array($serviceGroup)){
                    foreach ($serviceGroup as $skey => $svalue) {                
                        if (contain($skey, "attr")){
                            $servicename=$svalue['name'];
                        }else{
                            $methodsArr=$svalue;
                            if(is_array($methodsArr)){
                                foreach ($methodsArr as $method=>$methodGroup){
                                    $methods=array();
                                    if (is_array($methodGroup)){
                                        if (!array_key_exists("method attr",$methodGroup)){
                                            $methodGroup=$methodGroup['method'];
                                        }
                                    }
                                    if (is_array($methodGroup)){
                                        foreach ($methodGroup as $mkey => $mvalue) {
                                            if (contain($mkey, "attr")){
                                              $methodname=$mvalue['name'];
                                            }else{
                                                $paramArr=$mvalue;
                                                if (is_array($paramArr)){
                                                    foreach ($paramArr as $param=>$paramGroup){
                                                        $params=array();
                                                        if (count($paramGroup)==1){    
                                                             if (contain($param, "attr")){
                                                                $paramname=$paramGroup['name'];
                                                             }else{
                                                                 $params[$paramname]=$paramGroup;
                                                                 $methods[$methodname]=$params;
                                                             }
                                                        }else{
                                                            if (is_array($paramGroup)){
                                                                foreach ($paramGroup as $pkey => $pvalue) {
                                                                    if (contain($pkey, "attr")){
                                                                      $paramname=$pvalue['name'];
                                                                    }else{
                                                                      $params[$paramname]=$pvalue;
                                                                      $methods[$methodname]=$params;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }        
                                                }
                                            }
                                        }
                                    }
                                    $services[$servicename]=array('methods'=>$methods);
                                }
                            }
                        }
                    } 
                }
            }
        }
        if (count($services)>0){
            $result=$services;
        }
        return $result;
    }
    
    /**
     * 生成所有服务的配置xml文件信息
     */
    public static function createConfigForAllService()
    {
       header('Content-type: application/xml');
       $serviceFiles=UtilFileSystem::getAllFilesInDirectory(dirname(__FILE__).DIRECTORY_SEPARATOR);
       $result="<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
       $result.="\r\n<services>";       
       foreach ($serviceFiles as $key=> $serviceFile) {
           if (contain($serviceFile, "Config_Service")||
                contain($serviceFile, "Manager_Service")||
                contain($serviceFile, "api")||
                contain($serviceFile, "router")){ 
                unset($serviceFiles[$key]);
           }else{
                $servicesArray=self::servicetoArray($serviceFile);
                $servicename=basename($serviceFile, ".php");
                $result.="\r\n  <service name=\"$servicename\">\r\n     <methods>";
                if (is_array($servicesArray)){
                    $methodsArray=$servicesArray[$servicename]["methods"];
                    foreach ($methodsArray as $name=>$params){
                        $result.="\r\n          <method name=\"$name\">";
                        foreach ($params as $name => $value) {
                            $result.="\r\n              <param name=\"$name\">$value</param>";
                        }
                        $result.="\r\n          </method>";
                    }
                }
                $result.="\r\n      </methods>\r\n</service>";   
           }
       }                
       $result.="\r\n</services>";   
       return $result;
    }    
    
    /**
     * 为指定服务生成配置xml文件信息
     * @param string $serviceFile 文件名称
     */
    public static function createConfigForService($serviceFile)
    {
        header('Content-type: application/xml');        
        $servicesArray=self::servicetoArray($serviceFile);
        $servicename=basename($serviceFile, ".php");
        $result="<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        $result.="\r\n<services>";    
        $result.="\r\n   <service name=\"$servicename\">\r\n     <methods>";
        if (is_array($servicesArray)){
            $methodsArray=$servicesArray[$servicename]["methods"];
            foreach ($methodsArray as $name=>$params){
                $result.="\r\n           <method name=\"$name\">";
                foreach ($params as $name => $value) {
                    $result.="\r\n              <param name=\"$name\">$value</param>";
                }
                $result.="\r\n          </method>";
            }
        }
        $result.="\r\n      </methods>\r\n  </service>";
        $result.="\r\n</services>";  
        return $result;        
    }
    
    /**
     * @param string $serviceFile 文件名称
     * @return array 
     */
    private static function servicetoArray($serviceFile)
    {        
        $servicename=basename($serviceFile, ".php");
        $result=null;
        $services=array();
        if (class_exists($servicename)){
           $service=new ReflectionClass($servicename);               
           $methods=$service->getMethods();
           $methodsArr=array();
           foreach ($methods as $method) {
               if ($method->isPublic()){
                   $methodname=$method->getName();
                   $params=$method->getParameters();
                   $paramArr=array();
                   $count=1;
                   foreach ($params as $i => $param) {
                       $paramname=$param->getName();
                       if ($paramname=="formHandler"){
                         $count+=1;  
                         $paramArr[$paramname]=true;
                       }else{
                           if ($paramname!="id"){
                             $paramArr[$paramname]="";
                           }
                       }
                       $paramArr["len"]=$count;
                       $methodsArr[$methodname]=$paramArr;
                   }
               }
               $services[$servicename]=array('methods'=>$methodsArr);
               unset($services[$servicename]['methods']["__set"]);
               unset($services[$servicename]['methods']["__get"]);
           }
        }
        if (count($services)>0){
            $result=$services;
        }
        
        //print_r($result);
        return $result;
    }
}

?>
