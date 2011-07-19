<?php
/**
 +---------------------------------------<br/>
 * 所有配置工具类的父类
 * 读取以下文件类型配置信息,<br/>
 * 现支持php,ini,xml.yaml<br/>
 +---------------------------------------<br/>
 * @category betterlife
 * @package util.config
 * @author skygreen
 */
class UtilConfig extends Util{
   public $_settings = array(); 
   private static $config_xml=1;
   private static $config_ini=2;  
   private static $config_yaml=3;
   private static $config_php=4;  
   private static $current;                        
   public static $config=1;
   
   
   public static function Instance(){
      switch (self::$config){
          case self::$config_xml:
               self::$current=new UtilConfigXml();
               break;    
          case self::$config_ini:
               self::$current=new UtilConfigIni();
               break; 
          case self::$config_yaml:
               self::$current=new UtilConfigYaml();
               break; 
          case self::$config_php:
               self::$current=new UtilConfigPhp(); 
               break; 
      } 
      return self::$current;
   }
   
   /**
    * 获取某些设置的值  
    * @param unknown_type $var
    * @return unknown
    */
   public function get($var) {
     $var = explode('.', $var);
     $result = $this->_settings;
     foreach ($var as $key) {
        if (!isset($result[$key])) { return false; }
        $result = $result[$key];
     }
     return $result;
   }

   public function load() {
        trigger_error ('Not yet implemented', E_USER_ERROR);
   }
}
?>
