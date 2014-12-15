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
		header('Content-Type:text/javascript;charset=UTF-8');
		// convert API config to Ext.Direct spec
		$actions = array();
		foreach (self::$configApi as $aname => $a) {
			$methods = array();
			foreach ($a['methods'] as $mname => $m) {
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

		if (contain(strtolower(php_uname()),"darwin")){
			$file_sub_dir=str_replace("/", DS, dirname($_SERVER["SCRIPT_FILENAME"])).DS;
			if (contain($file_sub_dir,"home".DS))
				$file_sub_dir=substr($file_sub_dir,0,strpos($file_sub_dir,"home".DS));
			$domainSubDir=str_replace($_SERVER["DOCUMENT_ROOT"]."/", "", $file_sub_dir);
			if(!endwith($urlbase,$domainSubDir))$urlbase.=$domainSubDir;
		}

		$cfg = array(
			'url' =>  $urlbase.self::$router_url,
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