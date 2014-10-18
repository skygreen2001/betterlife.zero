<?php
/**
 +-------------------------------------<br/>
 * 工具类：读取php配置文件类 <br/>
 +-------------------------------------<br/>
 * @category betterlife
 * @package util.config
 * @subpackage php
 * @author skygreen
 */
class UtilConfigPhp extends UtilConfig{
   public function load ($file) {
        if (file_exists($file) == false) { return false; }
        // Include file
        include ($file);
        unset($file);   //销毁指定变量
        $vars = get_defined_vars(); //返回所有已定义变量的列表,数组,变量包括服务器等相关变量,
        //通过foreach吧$file引入的变量给添加到$_settings这个成员数组中去.
        foreach ($vars as $key => $val) {
             if ($key == 'this') continue;
             $this->_settings[$key] = $val;
        }
    }

    /**
    * 调用方法
    */
    public static function main(){
        // Load settings (PHP)
        $settings = new UtilConfigPhp();

        $settings->load(__DIR__.DS."setting.php");
        echo 'PHP:'. $settings->get('db.host').'';
    }
}
?>
