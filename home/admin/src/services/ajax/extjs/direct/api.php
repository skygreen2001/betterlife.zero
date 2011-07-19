<?php
require_once("../../../../../../../init.php");

/**
 * ExtJs的Ext Direct Config配置类
 */
class ConfigApi{
    /**
     * Ext Direct Remote通信配置API
     * @var array 
     */
    private static $configApi;
    
    private static $router_url="home/admin/src/services/ajax/extjs/direct/router.php";
    /**
     * 加载Action的配置文件
     */
    public static function init()
    {
        self::$configApi=Config_Service::serviceConfig();
    }
    /**
     * 渲染生成Ext.Direct的规格说明
     */
    public static function render()
    {
        header('Content-Type: text/javascript');
        // convert API config to Ext.Direct spec
        $actions = array();
        foreach (self::$configApi as $aname => &$a) {
            $methods = array();
            foreach ($a['methods'] as $mname => &$m) {
                $md = array(
                    'name' => $mname,
                    'len' => $m['len']
                );
                if (isset($m['formHandler']) && $m['formHandler']) {
                    $md['formHandler'] = true;
                }
                $methods[] = $md;
            }
            $actions[$aname] = $methods;
        }
        $urlbase=UtilNet::urlbase();
        $cfg = array(
            'url' =>  $urlbase. self::$router_url,
            'type' => 'remoting',
            'actions' => $actions
        );
        echo 'Ext.app.REMOTING_API = ';
        echo json_encode($cfg);
        echo ';';
    }
}

ConfigApi::init();
ConfigApi::render();

?>