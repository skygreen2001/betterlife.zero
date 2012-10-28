<?php
/**
 +---------------------------------<br/>
 * 工具类:自动生成代码-一键生成前后台所有模板文件<br/>
 +---------------------------------<br/>  
 * @category betterlife
 * @package core.autoCode   
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCodeOneKey extends AutoCode
{            
    /**
     * 预先校验表定义是否有问题
     */
    public static function validator()
    {
        self::init();
        $table_error=array("nocomment"=>array(),"column_nocomment"=>array());
        $isValid=true;
        foreach (self::$fieldInfos as $tablename=>$fieldInfo){
            $tableCommentKey=self::tableCommentKey($tablename);
            if (empty($tableCommentKey)){
                $table_error["nocomment"][]=$tablename;    
            }
            foreach ($fieldInfo as $fieldname=>$field)
            {       
                $field_comment=$field["Comment"];  
                if (empty($field_comment)){
                    $table_error["column_nocomment"][$tablename]=$fieldname;    
                }
            }
        }
        if (count($table_error["nocomment"])>0){
            $isValid=false;
            echo "<font color='#00FF00'>&nbsp;&nbsp;/".str_repeat("*",40)."以下表无注释,请添加以下表的注释".str_repeat("*",40)."</font></a><br/>";  
            foreach ($table_error["nocomment"] as $tablename) {
                echo $tablename."<br/>";
            }
        }
        if (count($table_error["column_nocomment"])>0){
            $isValid=false;
            echo "<font color='#00FF00'>&nbsp;&nbsp;/".str_repeat("*",40)."以下表列举的列无注释,请添加以下表列举的列的注释".str_repeat("*",40)."</font></a><br/>";  
            foreach ($table_error["column_nocomment"] as $tablename=>$fieldname) {
                echo $tablename."->".$fieldname."<br/>";
            }
        }
        return $isValid;
    }
    
    /**
     * 自动生成配置
     */
    public static function CreateAutoConfig()
    {
        $filename=Gc::$nav_root_path."tools".DIRECTORY_SEPARATOR."tools".DIRECTORY_SEPARATOR."autoCode".DIRECTORY_SEPARATOR."autocode_create.config.xml";
        $classes=array("class"=>array());
        self::init();
        foreach (self::$fieldInfos as $tablename=>$fieldInfo){
            if (contain($tablename,Config_Db::TABLENAME_RELATION)){
                continue;
            }
            $classname=self::getClassname($tablename);
            $showfieldname=self::getShowFieldNameByClassname($classname);
            $classes["class"][]=array(
                '@attributes' => array(
                    "name"=>$classname
                ),
                "conditions"=>array(
                    "condition"=>array(
                        array(
                            "@value"=>$showfieldname
                        )
                    )
                ),
            );
        }
        $result =UtilArray::saveXML($filename,$classes,"classes");
        return true;
    }

    /**
     * 自动生成代码-一键生成前后台所有模板文件
     */
    public static function AutoCode()
    {
        AutoCodeFoldHelper::foldEffectReady();
        //生成实体数据对象类
        AutoCodeDomain::$save_dir =self::$save_dir;
        AutoCodeDomain::$type     =2; 
        AutoCodeFoldHelper::foldbeforedomain();                    
        AutoCodeDomain::AutoCode();    
        AutoCodeFoldHelper::foldafterdomain();
        AutoCode::$isNoOutputCss=false;
        
        //生成提供服务类[前端和后端基于Ext的Service类]
        AutoCodeService::$save_dir =self::$save_dir;  
        AutoCodeFoldHelper::foldbeforeservice();  
        AutoCodeService::$type     =2;
        AutoCodeService::AutoCode();       
        AutoCodeService::$type     =3;
        AutoCodeService::AutoCode();  
        AutoCodeFoldHelper::foldafterservice();
        
        //生成Action类[前端和后端]
        AutoCodeAction::$save_dir =self::$save_dir;   
        AutoCodeFoldHelper::foldbeforeaction();
        AutoCodeAction::$type     =0;
        AutoCodeAction::AutoCode(); 
        AutoCodeAction::$type     =1;     
        AutoCodeAction::AutoCode();        
        AutoCodeAction::$type     =2;                     
        AutoCodeAction::AutoCode();    
        AutoCodeFoldHelper::foldafteraction();
        
        //生成前端表示层
        AutoCodeFoldHelper::foldbeforeviewdefault();
        AutoCodeViewDefault::$save_dir =self::$save_dir;
        AutoCodeViewDefault::$type     =0;              
        AutoCodeViewDefault::AutoCode();  
        AutoCodeViewDefault::$type     =1;    
        AutoCodeViewDefault::AutoCode();  
        AutoCodeFoldHelper::foldafterviewdefault();        
        
        //生成后端表示层
        AutoCodeViewExt::$save_dir =self::$save_dir;
        AutoCodeFoldHelper::foldbeforeviewext();
        AutoCodeViewExt::AutoCode(); 
        AutoCodeFoldHelper::foldafterviewext();        
        echo "</div>";
    }

    /**
     * 用户输入需求
     */
    public static function UserInput()
    {                 
        parent::UserInput("一键生成前后台所有模板文件的输出文件路径参数");  
    }

}
?>
