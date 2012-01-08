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
     * 自动生成代码-一键生成前后台所有模板文件
     */
    public static function AutoCode()
    {
        echo "<font color='#00FF00'>/***********************************生成实体数据对象类:start********************************************</font><br/>";
        //生成实体数据对象类
        AutoCodeDomain::$save_dir =self::$save_dir;
        AutoCodeDomain::$type     =2;
        AutoCodeDomain::AutoCode();
        echo "<font color='#00FF00'>/***********************************生成实体数据对象类:end  ********************************************</font><br/><br/>";

        echo "<font color='#00FF00'>/***********************************生成提供服务类[前端和后端基于Ext的Service类]:start********************************************</font><br/>";
        //生成提供服务类[前端和后端基于Ext的Service类]
        AutoCodeService::$save_dir =self::$save_dir;   
        AutoCodeService::$type     =2;
        AutoCodeService::AutoCode();       
        AutoCodeService::$type     =3;
        AutoCodeService::AutoCode();  
        echo "<font color='#00FF00'>/***********************************生成提供服务类[前端和后端基于Ext的Service类]:end  ********************************************</font><br/><br/>";
        
        echo "<font color='#00FF00'>/***********************************生成Action类[前端和后端]:start********************************************</font><br/>";
        //生成Action类[前端和后端]
        AutoCodeAction::$save_dir =self::$save_dir;   
        AutoCodeAction::$type     =1;
        AutoCodeAction::AutoCode();       
        AutoCodeAction::$type     =2;
        AutoCodeAction::AutoCode();   
        echo "<font color='#00FF00'>/***********************************生成Action类[前端和后端]:end  ********************************************</font><br/><br/>";
                               
        echo "<font color='#00FF00'>/***********************************生成前端表示层:start********************************************</font><br/>"; 
        //生成前端表示层
        AutoCodeViewDefault::$save_dir =self::$save_dir;
        AutoCodeViewDefault::AutoCode();  
        echo "<font color='#00FF00'>/***********************************生成前端表示层:end  ********************************************</font><br/><br/>";
                                      
        echo "<font color='#00FF00'>/***********************************生成后端表示层:start********************************************</font><br/>";           
        //生成后端表示层
        AutoCodeViewExt::$save_dir =self::$save_dir;    
        AutoCodeViewExt::AutoCode(); 
        echo "<font color='#00FF00'>/***********************************生成后端表示层:end  ********************************************</font><br/><br/>";   
    }

    /**
     * 用户输入需求
     */
    public static function UserInput()
    {                 
        parent::UserInput("-一键生成前后台所有模板文件的输出文件路径参数");  
    }
    
}
?>
