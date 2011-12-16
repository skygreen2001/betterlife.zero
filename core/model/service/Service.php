<?php
/**
 +--------------------------------------------------<br/>
 * 所有Service的父类<br/>
 +---------------------------------------------------
 * @category betterlife  
 * @package core.model
 * @subpackage service
 * @author skygreen 
 */
class Service extends Object {
    /**
     * @var IDao 当前使用的数据库调用对象
     */
    private static $currentDao;
    protected static function dao() {
        if (empty(self::$currentDao)) {
            self::$currentDao=Manager_Db::newInstance()->dao();
        }
        return self::$currentDao;
    }
    
    /**
     * 转换成数组
     * @return int 
     */
    public static function toArray(){        
        $servicename=get_called_class();
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
                       if ($param->isDefaultValueAvailable()){
                          $paramArr[$paramname]=$param->getDefaultValue();
                       }else{
                           $paramArr[$paramname]="无默认值";
                       }
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
