<?php
/**
 +---------------------------------------<br/>
 * 所有枚举类型的父类<br/>
 +---------------------------------------
 * @category betterlife
 * @package core
 * @author skygreen
 */
class Enum {
    /**
     * 获取所有的枚举值
     */
    public static function allEnums() {
        $class = new ReflectionClass(get_called_class());
        $consts = $class->getConstants();
        return $consts;
    }

    /**
     * 查看指定的枚举值是否存在
     * @param string $value 指定的枚举值
     * @return bool 指定的枚举值是否存在
     */
    public static function isEnumValue($value){
       if (!empty($value)){
           $class = new ReflectionClass(get_called_class());
           $consts = $class->getConstants();        
           //$consts= self::allEnums();
           if (isset ($consts)){
               if (in_array($value,$consts)){
                   return true;
               }               
           }                   
       }
       return false;
    }

    /**
     * 查看指定的枚举键是否存在
     * @param string $value 指定的枚举键
     * @return bool 指定的枚举键是否存在
     */
    public static function isEnumKey($key){
       if (!empty($key)){  
           $consts= self::allEnums();
           if (isset ($consts)){
               if (array_key_exists($key,$consts)){
                   return true;
               }
           }
       }
       return false;
    }
}
?>
