<?php
  /**
  * 数据对象常用的一些方法。
  */
  class DataObjectFunc
  {
    //<editor-fold defaultstate="collapsed" desc="魔术方法">
    /**
    * 对应数据对象的__call方法
    * 
    * @param string $dataobject 当前数据对象
    * @param string $method 方法名
    * @param array $arguments 传递的变量数组
    */
    public static function call($dataobject,$method, $arguments)
    {
        if (strpos($method,"set")!==false) {
            $property=substr($method,strlen("set"),strlen($method));
            $property{0}=strtolower($property{0});
        //            $property=lcfirst(substr($method,strlen("set"),strlen($method)));
            $dataobject->{$property}=$arguments[0];
        }else if (strpos($method,"get")!==false) {
            $property=substr($method,strlen("get"),strlen($method));
            $property{0}=strtolower($property{0});
        //            $property=lcfirst(substr($method,strlen("get"),strlen($method)));
            return $dataobject->{$property};
        }else {
            //处理表之间一对一，一对多，多对多的关系
            $isRelation=false;//是否存在关系
            $relationData=$dataobject->getMutualRelation($method,&$isRelation);
            if ($isRelation) {
                return $relationData;
            }
        }          
    }
      
    /**
     * 对应数据对象的__get方法
     * @param string $dataobject 当前数据对象
     * 可设定对象未定义的成员变量[但不建议这样做]<br/>
     * 类定义变量访问权限设定需要是pulbic
     * @param mixed $property 属性名
     * @return mixed 属性值
     */
    public static function get($dataobject,$property) 
    {
        if (method_exists($dataobject, "get".ucfirst($property))) {
            $methodname="get".ucfirst($property);
            return $dataobject->{$methodname}();
        }else {
            //处理表之间一对一，一对多，多对多的关系
            $isRelation=false;//是否存在关系
            $relationData=$dataobject->getMutualRelation($property,&$isRelation);
            if ($isRelation) {
                return $relationData;
            }else {
                if (!property_exists($dataobject,$property)) {
                    return @$dataobject->{$property};
                }
            }
        }
    }      
      
    /**
     * 对应数据对象的__set方法
     * 可设定对象未定义的成员变量[但不建议这样做]<br/>
     * 类定义变量访问权限设定需要是pulbic
     * @param string $dataobject 当前数据对象
     * @param mixed $property 属性名
     * @param mixed $value 属性值
     */
    public function set($dataobject,$property, $value)
    {
        if (method_exists($dataobject, "set".ucfirst($property))) {
            $methodname="set".ucfirst($property);
            $dataobject->{$methodname}($value);
        }else {
            if (!property_exists($dataobject,$property)) {
                $dataobject->{$property}=$value;
            }
        }
    }      
    //</editor-fold>  
    
    //<editor-fold defaultstate="collapsed" desc="其他">
    /**
    * 输出显示DataObject对象<br/>
    * 通常以 echo $dataobject。<br/>  
    * @param string $dataobject 当前数据对象
    */
    public static function toString($dataobject)
    {
        if (Gc::$dev_debug_on){            
            return print_pre($dataobject)."";
        }else{
            $classname=$dataobject->classname();   
            $result="<pre>";       
            $result.=$classname." DataObject\r\n{\r\n";
            $dataobject=clone $dataobject;
            $dataobjectArr=$dataobject->toArray();
            $dataobjectProperties=UtilReflection::getClassPropertiesInfo($dataobject); 
            foreach($dataobjectArr as $key=>$value)
            {
                $access="";
                if (array_key_exists($key,$dataobjectProperties)){
                    $propertyInfo=$dataobjectProperties[$key];
                    if (!empty($propertyInfo)&& array_key_exists("access",$propertyInfo)){
                        $access=":".$propertyInfo["access"];
                    }
                }                        
                $result.="      [".$key.$access."]"." => ".$value."\r\n";
            }
            $result.="}";
            $result.="</pre>";
            return $result;
        }          
    }
    

    /**
     * 将数据对象转换成Json类型格式
     * @param string $dataobject 当前数据对象
     * @param $isAll 是否对象所有的field都要生成，包括没有内容或者内容为空的field
    * @return Json格式的数据格式的字符串。
    */
    public static function toJson($dataobject,$isAll=false)
    {
       $object_arr=UtilObject::object_to_array($dataobject,$isAll);
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
     * 
     * 对应数据对象的updateProperties方法
     * @param string $classname 当前数据对象类名
     * @return boolen 是否更新成功；true为操作正常<br/>
     */
    public static function updateProperties($classname,$sql_id,$array_properties) {
        $tablename=Config_Db::orm($classname);
        $_SQL=new Crud_Sql_Update();
        $_SQL->isPreparedStatement=false;
        $sQuery=$_SQL->update($tablename)->set($array_properties)->where($sql_id)->result();
        return DataObject::dao()->sqlExecute($sQuery);
    }
    //</editor-fold>      
  }
?>
