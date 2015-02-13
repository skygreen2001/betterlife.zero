<?php
/**
 +-------------------------------------<br/>
 * 工具类：读取Json配置文件类<br/>
 +-------------------------------------<br/>
 * @category betterlife
 * @package util.config
 * @subpackage ini
 * @author skygreen
 */
class UtilConfigJson extends UtilConfig
{
    /**
     * 加载Json配置文件
     */
    public function load ($file) {
        if (file_exists($file) == false) { return false; }
        $this->_settings = json_decode(file_get_contents($file),true);
    }

    /**
     * 调用方法
     */
    public static function main(){
        $settings = new UtilConfigJson();
        $settings->load(__DIR__.DS.'config.json');
        echo 'Json: host-' . $settings->get('db.host') . ',debug-'.$settings->get('debug') ;
    }
}
?>
