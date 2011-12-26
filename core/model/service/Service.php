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
     * 获取数据对象属性映射表字段意义
     * @param string $dataobject 当前数据对象
     * 可设定对象未定义的成员变量[但不建议这样做]<br/>
     * @return array 表列名列表；键:列名,值:列注释说明
     */
    public static function fieldsMean($tablename)
    {
       return Manager_Db::newInstance()->dbinfo()->fieldMapNameList($tablename);  
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
