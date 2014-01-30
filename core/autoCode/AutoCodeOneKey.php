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

        $dest_directory=Gc::$nav_root_path."tools".DIRECTORY_SEPARATOR."tools".DIRECTORY_SEPARATOR."autoCode".DIRECTORY_SEPARATOR;
        $filename=$dest_directory."autocode.config.xml";
        AutoCodeValidate::run();
        if (!file_exists($filename)){
            AutoCodeConfig::run();
        }
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
    public static function UserInput($title=null,$inputArr=null)
    {
        parent::UserInput("一键生成前后台所有模板文件的输出文件路径参数");
    }

}
?>
