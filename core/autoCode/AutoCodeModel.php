<?php
/**
 +---------------------------------<br/>
 * 工具类:自动生成代码-生成单张表或者对应类的前后台所有模板文件<br/>
 +---------------------------------<br/>
 * @category betterlife
 * @package core.autoCode
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCodeModel extends AutoCode
{
    /**
     * 自动生成代码-一键生成前后台所有模板文件
     * @param array|string $table_names
     * 示例如下：
     *  1.array:array('bb_user_admin','bb_core_blog')
     *  2.字符串:'bb_user_admin,bb_core_blog'
     */
    public static function AutoCode($table_names="")
    {
        $dest_directory=Gc::$nav_root_path."tools".DIRECTORY_SEPARATOR."tools".DIRECTORY_SEPARATOR."autoCode".DIRECTORY_SEPARATOR;
        $filename=$dest_directory."autocode.config.xml";
        AutoCodeValidate::run($table_names);
        if (!file_exists($filename)){
            AutoCodeConfig::run($table_names);
            die("&nbsp;&nbsp;自动生成代码的配置文件已生成，请再次运行以生成所有web应用代码！");
        }
        AutoCodeFoldHelper::foldEffectReady();
        //生成实体数据对象类
        AutoCodeDomain::$save_dir =self::$save_dir;
        AutoCodeDomain::$type     =2;
        AutoCodeFoldHelper::foldbeforedomain();
        AutoCodeDomain::AutoCode($table_names);
        AutoCodeFoldHelper::foldafterdomain();
        AutoCode::$isNoOutputCss=false;

        //生成提供服务类[前端和后端基于Ext的Service类]
        AutoCodeService::$save_dir =self::$save_dir;
        AutoCodeFoldHelper::foldbeforeservice();
        AutoCodeService::$type     =2;
        AutoCodeService::AutoCode($table_names);
        AutoCodeService::$type     =3;
        AutoCodeService::AutoCode($table_names);
        AutoCodeFoldHelper::foldafterservice();

        //生成Action类[前端和后端]
        AutoCodeAction::$save_dir =self::$save_dir;
        AutoCodeFoldHelper::foldbeforeaction();
        AutoCodeAction::$type     =0;
        AutoCodeAction::AutoCode($table_names);
        AutoCodeAction::$type     =1;
        AutoCodeAction::AutoCode($table_names);
        AutoCodeAction::$type     =2;
        AutoCodeAction::AutoCode($table_names);
        AutoCodeFoldHelper::foldafteraction();

        //生成前端表示层
        AutoCodeFoldHelper::foldbeforeviewdefault();
        AutoCodeViewDefault::$save_dir =self::$save_dir;
        AutoCodeViewDefault::$type     =0;
        AutoCodeViewDefault::AutoCode($table_names);
        AutoCodeViewDefault::$type     =1;
        AutoCodeViewDefault::AutoCode($table_names);
        AutoCodeFoldHelper::foldafterviewdefault();

        //生成后端表示层
        AutoCodeViewExt::$save_dir =self::$save_dir;
        AutoCodeFoldHelper::foldbeforeviewext();
        AutoCodeViewExt::AutoCode($table_names);
        AutoCodeFoldHelper::foldafterviewext();
        echo "</div>";
    }

    /**
     * 用户输入需求
     */
    public static function UserInput($title=null,$inputArr=null)
    {

        $default_dir=Gc::$nav_root_path."model".DIRECTORY_SEPARATOR;
        self::$save_dir=$default_dir;
        self::init();
        $title="一键生成指定表前后台所有模板";
        $inputArr=array();
        foreach (self::$tableList as $tablename) {
            $inputArr[$tablename]=$tablename;
        }
        echo  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
                <html lang="zh-CN" xml:lang="zh-CN" xmlns="http://www.w3.org/1999/xhtml">';
        echo "<head>\r\n";
        echo UtilCss::form_css()."\r\n";
        $url_base=UtilNet::urlbase();
        echo "</head>";
        echo "<body>";
        echo "<br/><br/><br/><h1 align='center'>$title</h1>";
        echo "<div align='center' height='450'>";
        echo "<form>";
        echo "  <div style='line-height:1.5em;'>";
        echo "      <label>输出文件路径:</label><input style=\"width:400px;text-align:left;padding-left:10px;\" type=\"text\" name=\"save_dir\" value=\"$default_dir\" id=\"save_dir\" />";
        if (!empty($inputArr)){
            echo "<br/><br/>
                    <label>&nbsp;&nbsp;&nbsp;选择需要生成的表:</label><select multiple='multiple' size='8' style='height:320px;' name=\"table_names[]\">";
            foreach ($inputArr as $key=>$value) {
                echo "        <option value='$key'>$value</option>";
            }
            echo "      </select>";
        }
        echo "  </div>";
        echo "  <input type=\"submit\" value='生成' /><br/>";
        echo "</form>";
        echo "</div>";
        echo "</body>";
        echo "</html>";
    }

}
?>
