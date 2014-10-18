<?php
/**
 +-------------------------------------<br/>
 * 工具类：读取Ini配置文件类<br/>
 +-------------------------------------<br/>
 * @category betterlife
 * @package util.config
 * @subpackage ini
 * @author skygreen
 */
class UtilConfigIni extends UtilConfig{
    public function load ($file) {
        if (file_exists($file) == false) { return false; }
        $this->_settings = parse_ini_file ($file, true);
    }

    /**
    * 调用方法
    */
    public static function main(){
        $settings = new UtilConfigIni();
        $settings->load(__DIR__.DS.'setting.ini');
        echo 'INI: ' . $settings->get('db.host') . '';
    }
}
?>
