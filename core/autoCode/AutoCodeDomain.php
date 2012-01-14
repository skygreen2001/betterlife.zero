<?php     
/**
 +---------------------------------<br/>
 * 工具类:自动生成代码-实体类<br/>
 +---------------------------------<br/>  
 * @category betterlife
 * @package core.autoCode   
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCodeDomain extends AutoCode
{       
    /**
     *实体数据对象类文件所在的路径 
     */
    public static $domain_dir="domain";      
    /**
     * 实体数据对象类完整的保存路径
     */
    public static $domain_dir_full; 
    /**
     *实体数据对象类文件所在的路径 
     */
    public static $enum_dir="enum"; 
    /**
     * 生成枚举类型类 
     */
    public static $enumClass;
    /**
     * 数据对象生成定义的方式<br/>
     * 1.所有的列定义的对象属性都是private,同时定义setter和getter方法。
     * 2.所有的列定义的对象属性都是public。
     */
    public static $type;
    /**
     * 自动生成代码-实体类
     */
    public static function AutoCode()
    {               
        self::$app_dir=Gc::$appName;
        self::$domain_dir_full=self::$save_dir.DIRECTORY_SEPARATOR.self::$app_dir.DIRECTORY_SEPARATOR.self::$dir_src.DIRECTORY_SEPARATOR.self::$domain_dir.DIRECTORY_SEPARATOR;
                                
        $tableList=Manager_Db::newInstance()->dbinfo()->tableList();
        $fieldInfos=array();
        foreach ($tableList as $tablename){
           $fieldInfoList=Manager_Db::newInstance()->dbinfo()->fieldInfoList($tablename); 
           foreach($fieldInfoList as $fieldname=>$field){
               $fieldInfos[$tablename][$fieldname]["Field"]=$field["Field"];
               $fieldInfos[$tablename][$fieldname]["Type"]=$field["Type"];
               $fieldInfos[$tablename][$fieldname]["Comment"]=$field["Comment"];
           }
        }

        $tableInfoList=Manager_Db::newInstance()->dbinfo()->tableInfoList(); 
        echo UtilCss::form_css()."\r\n";
        self::$enumClass="";
        foreach ($fieldInfos as $tablename=>$fieldInfo){
           //print_r($fieldInfo);
           //echo("<br/>");
           $definePhpFileContent=self::tableToDataObjectDefine($tablename,$tableInfoList,$fieldInfo);
           if (isset(self::$save_dir)&&!empty(self::$save_dir)&&isset($definePhpFileContent)){
               $classname=self::saveDataObjectDefineToDir($tablename,$definePhpFileContent);
               echo "生成导出完成:$tablename->$classname!<br/>";   
           }else{
               echo $definePhpFileContent."<br/>";
           }
           self::tableToEnumClass($tablename,$tableInfoList,$fieldInfo);             
        }   
        echo "<br/><font color='#FF0000'>生成枚举类型</font><br/>"; 
        echo self::$enumClass;  
    }

    /**
     * 用户输入需求
     */
    public static function UserInput()
    {   
        $inputArr=array(
            "1"=>"对象属性都是private,定义setter和getter方法。",
            "2"=>"所有的列定义的对象属性都是public"
        );    
        parent::UserInput("需要定义生成实体类的输出文件路径参数",$inputArr);   
    }

    /**
     * 将表枚举列生成枚举类型类                       
     * @param string $tablename 表名
     * @param array $tableInfoList 表信息列表
     * @param array $fieldInfo 表列信息列表
     */
    private static function tableToEnumClass($tablename,$tableInfoList,$fieldInfo)
    {
        $category  = Gc::$appName;
        $author    = self::$author;                  
        foreach ($fieldInfo as $fieldname=>$field){
            $datatype =self::comment_type($field["Type"]);
            if ($datatype=='enum'){
                $enumclassname=self::enumClassName($fieldname,$tablename); 
                $comment_arr=preg_split("/[\s,]+/", $field["Comment"]);
                unset($comment_arr[0]);
                if ((!empty($comment_arr))&&(count($comment_arr)>0)){
                    $enum_columnDefine=array();
                    foreach ($comment_arr as $comment) {
                        if (!UtilString::is_utf8($comment)){
                            $comment=UtilString::gbk2utf8($comment);    
                        }
                        $part_arr=preg_split("/[.:：]+/", $comment);
                        if ((!empty($part_arr))&&(count($part_arr)==2)){         
                            if (is_numeric($part_arr[0])){
                               $cn_en_arr=explode("-",$part_arr[1]);
                               if ((!empty($cn_en_arr))&&(count($cn_en_arr)==2)){ 
                                   $enum_columnDefine[]=array(
                                        'name'=>strtolower($cn_en_arr[1]),
                                        'value'=>$part_arr[0],
                                        'comment'=>$cn_en_arr[0]
                                   ); 
                               } 
                            }else{
                               $enum_columnDefine[]=array(
                                    'name'=>strtolower($part_arr[0]),
                                    'value'=>strtolower($part_arr[0]),
                                    'comment'=>$part_arr[1]
                               ); 
                            }
                        }
                    }  
                }
                if (isset($enum_columnDefine)&&(count($enum_columnDefine)>0))
                {
                    $comment=$field["Comment"];
                    if (contains($comment,array("\r","\n"))){
                        $comment=preg_split("/[\s,]+/", $comment);    
                        $comment=$comment[0]; 
                    }                      
                    $result="<?php\r\n".                    
                             "/**\r\n".
                             " *---------------------------------------<br/>\r\n".
                             " * 枚举类型:$comment  <br/> \r\n".
                             " *---------------------------------------<br/>\r\n".
                             " * @category $category\r\n".
                             " * @package domain\r\n".
                             " * @subpackage enum \r\n".
                             " * @author $author\r\n".
                             " */\r\n".
                             "class $enumclassname extends Enum\r\n".
                             "{\r\n";
                    foreach ($enum_columnDefine as $enum_column) {     
                        $enumname=strtoupper($enum_column['name']) ;
                        $enumvalue=$enum_column['value'];     
                        $enumcomment=$enum_column['comment'];
                        $result.="    /**\r\n".
                                 "     * $comment:$enumcomment\r\n".
                                 "     */\r\n".
                                 "    const $enumname='$enumvalue';\r\n";
                    }    
                    $result.="\r\n";

                    $comment  =str_replace("\r\n", "     * ", $field["Comment"]);
                    $comment  =str_replace("\r", "     * ", $comment);
                    $comment  =str_replace("\n", "     * ", $comment);
                    $comment  =str_replace("     * ", "\r\n     * ", $comment);
                    $result.="    /** \r\n".
                             "     * ".$comment."\r\n".
                             "     */\r\n".
                             "    public static function {$fieldname}Show(\${$fieldname})\r\n". 
                             "    {\r\n".
                             "       switch(\${$fieldname}){ \r\n";
                                                                       
                    foreach ($enum_columnDefine as $enum_column) {     
                        $enumname=strtoupper($enum_column['name']) ;      
                        $enumcomment=$enum_column['comment'];     
                        $result.="            case self::{$enumname}:\r\n".                                    
                                 "                return \"{$enumcomment}\"; \r\n";  
                    }         
                    $result.="       }\r\n";                    
                    $result.="       return \"未知\";\r\n".
                             "    }\r\n";    
                    $result.="}\r\n".
                             "?>\r\n";
                    self::$enumClass.="生成导出完成:$tablename[$fieldname]->".self::saveEnumDefineToDir($enumclassname,$result)."!<br/>";
                }         
            }                     
        }
    }
    
    /**
     * 将表列定义转换成数据对象Php文件定义的内容
     * @param string $tablename 表名
     * @param array $tableInfoList 表信息列表
     * @param array $fieldInfo 表列信息列表
     */
    private static function tableToDataObjectDefine($tablename,$tableInfoList,$fieldInfo)
    {
        $result="<?php\r\n";
        if ($tableInfoList!=null&&count($tableInfoList)>0&&  array_key_exists("$tablename", $tableInfoList)){
            $table_comment=$tableInfoList[$tablename]["Comment"];
            $table_comment=str_replace("关系表","",$table_comment); 
            if (contain($table_comment,"\r")||contain($table_comment,"\n")){
                $table_comment_arr=preg_split("/[\s,]+/", $table_comment);  
                $table_comment=""; 
                foreach ($table_comment_arr as $tcomment){
                    $table_comment.=" * $tcomment<br/>\r\n";
                }  
            }else{
                $table_comment=" * ".$table_comment."\r\n";
            }
        }else{
            $table_comment="关于$tablename的描述";
        }    
        $category  = Gc::$appName;
        $author    = self::$author;
        $package   = self::getPackage($tablename);
        $classname = self::getClassname($tablename);                
        $result.="/**\r\n".
                 " +---------------------------------------<br/>\r\n".
                 "$table_comment".
                 " +---------------------------------------\r\n".
                 " * @category $category\r\n".
                 " * @package $package\r\n".
                 " * @author $author\r\n".
                 " */\r\n";
        $result  .="class $classname extends DataObject\r\n{\r\n";
        $datatype ="string";
        switch (self::$type) {
            case 2:
                $result.= '    //<editor-fold defaultstate="collapsed" desc="定义部分">'."\r\n";
                foreach ($fieldInfo as $fieldname=>$field){
                    if (self::isNotColumnKeywork($fieldname)){
                        $datatype =self::comment_type($field["Type"]);
                        $comment  =str_replace("\r\n", "     * ", $field["Comment"]);
                        $comment  =str_replace("\r", "     * ", $comment);
                        $comment  =str_replace("\n", "     * ", $comment);
                        $comment  =str_replace("     * ", "\r\n     * ", $comment);
                        $result  .= 
                                    "    /**\r\n".
                                    "     * ".$comment."\r\n".
                                    "     * @var $datatype\r\n".
                                    "     * @access public\r\n". 
                                    "     */\r\n".
                                    "    public \$".$fieldname.";\r\n";
                    }
                };
                $result.= "    //</editor-fold>\r\n";
                break;
            default:            
                $result.= '    //<editor-fold defaultstate="collapsed" desc="定义部分">'."\r\n";
                foreach ($fieldInfo as $fieldname=>$field){
                  if (self::isNotColumnKeywork($fieldname)){
                       $datatype=self::comment_type($field["Type"]);
                       $comment=str_replace("\r\n", "     * ", $field["Comment"]);
                       $comment=str_replace("\r", "     * ", $comment);
                       $comment=str_replace("\n", "     * ", $comment);
                       $comment=str_replace("     * ", "\r\n     * ", $comment);
                       $result.= 
                                "    /**\r\n".
                                "     * ".$comment."\r\n".
                                "     * @var $datatype\r\n".
                                "     * @access private\r\n". 
                                "     */\r\n".
                                "    private \$".$fieldname.";\r\n";
                    };
                }
                $result.= "    //</editor-fold>\r\n\r\n";
                $result.= '    //<editor-fold defaultstate="collapsed" desc="setter和getter">'."\r\n";
                foreach ($fieldInfo as $fieldname=>$field){
                    if (self::isNotColumnKeywork($fieldname)){
                        $result.= 
                            "    public function set".ucfirst($fieldname)."(\$".$fieldname.")\r\n".
                            "    {\r\n".
                            "        \$this->".$fieldname."=\$".$fieldname.";\r\n".
                            "    }\r\n";
                        $result.=
                            "    public function get".ucfirst($fieldname)."()\r\n".
                            "    {\r\n".
                            "        return \$this->".$fieldname.";\r\n".
                            "    }\r\n";
                    };
                }      
                $result.= "    //</editor-fold>\r\n";            
                break;
        }
        $result.="}\r\n";    
        $result.="?>";
        return $result;
    }

    /**
     *从表名称获取子文件夹的信息。
     * @param string $tablename 
     * @return string 返回对象所在的Package名 
     */
    private static function getPackage($tablename){  
        $pacre=str_replace(Config_Db::$table_prefix, "", $tablename);
        $pacre=str_replace(Config_Db::TABLENAME_RELATION,Config_Db::TABLENAME_DIR_RELATION, $pacre);      
        $package=str_replace("_", ".", $pacre);
        $packageSplit=explode(".", $package);
        unset($packageSplit[count($packageSplit)-1]);
        $package= implode(".", $packageSplit);      
        return $package;
    }

    /**
     * 是否默认的列关键字：id,committime,updateTime
     * @param type $fieldname 
     */
    private static function isNotColumnKeywork($fieldname)
    {
        if ($fieldname=="id"||$fieldname=="commitTime"||$fieldname=="updateTime"){
            return false;
        }else{    
            return true;
        }
    }

    /**
     * 将表中的类型定义转换成对象field的注释类型说明
     * @param string $type 
     */
    private static function comment_type($type)
    {
        if (UtilString::contain($type,"(")){
            list($typep,$length)=split('[()]', $type);      
        }else{
            $typep=$type;
        }
        switch ($typep) {
            case "int":
            case "enum":
            case "timestamp":
                return $typep; 
            case "bigint":            
                return "int";
            case "decimal":
                return "float";
            case "varchar":
                return "string";
            default:
                return "string";
        }      
    }

    /**
     * 保存生成的代码到指定命名规范的文件中  
     * @param string $tablename 表名称    
     * @param string $definePhpFileContent 生成的代码 
     */
    private static function saveDataObjectDefineToDir($tablename,$definePhpFileContent)
    {
        $package  =self::getPackage($tablename);
        $filename =self::getClassname($tablename).".php";
        $package  =str_replace(".", DIRECTORY_SEPARATOR, $package);   
        return self::saveDefineToDir(self::$domain_dir_full.$package,$filename,$definePhpFileContent);
    }    
    
    /**
     * 根据表列枚举类型生成枚举类名称 
     * @param string $columnname 枚举列名称
     * @param string $tablename 表名称    
     */
    private static function enumClassName($columnname,$tablename=null)
    {
        $enumclassname="Enum"; 
        if ((strtolower($columnname)=='type')||(strtolower($columnname)=='statue')||(strtolower($columnname)=='status')){ 
            $enumclassname.=self::getClassname($tablename).ucfirst($columnname);
        }else{  
            if (contain($columnname,"_")){
                $c_part=explode("_",$columnname); 
                foreach ($c_part as $column) {
                    $enumclassname.=ucfirst($column);
                }
            }else{
                $enumclassname.=ucfirst($columnname);    
            }  
        }   
        return $enumclassname;
    }
    
    /**                                                           
     * 保存生成的枚举类型代码到指定命名规范的文件中  
     * @param string $enumclassname 枚举类名称    
     * @param string $definePhpFileContent 生成的代码 
     */
    private static function saveEnumDefineToDir($enumclassname,$definePhpFileContent)
    {                                                                                 
        $filename = $enumclassname.".php";  
        return self::saveDefineToDir(self::$domain_dir_full.self::$enum_dir,$filename,$definePhpFileContent);
    }    
}

?>