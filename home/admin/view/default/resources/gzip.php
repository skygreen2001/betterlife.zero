<?php
/**
* @see http://mrthink.net/ue-php-gzip-function/
*/
require_once(dirname(__FILE__)."/../../../../../init.php");

UtilAjax::init();

if(extension_loaded('zlib')){//检查服务器是否开启了zlib拓展
	ob_start('ob_gzhandler');
}
header("Cache-Control: must-revalidate");
$offset = 60 * 60 * 24 * 3;
$ExpStr = "Expires: ".gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
header($ExpStr);
ob_start("compress");
function compress($buffer) {//去除文件中的注释
	  $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
	  return $buffer;
}
$jsFile = @$_GET['js'];
$cssFile=@$_GET['css'];
$group=@$_GET['g'];
$version = @$_GET['v'];

if (isset ($jsFile)){
	header("Content-type: text/javascript; charset: UTF-8");    
	if (EnumJsFramework::isEnumValue($group)) {
		$ajax_root=Gc::$nav_root_path."common".DIRECTORY_SEPARATOR."js".DIRECTORY_SEPARATOR."ajax".DIRECTORY_SEPARATOR;
		$jsFile=str_replace("/", DIRECTORY_SEPARATOR, $jsFile);
		switch ($group){
			case EnumJsFramework::JS_FW_JQUERY:         
			case EnumJsFramework::JS_FW_MOOTOOLS:   
				echo "请使用UtilAjax的load方法，直接加载".$group."的js文件；无需使用Gzip压缩！";
				die();
				break;
			case EnumJsFramework::JS_FW_PROTOTYPE:
			case EnumJsFramework::JS_FW_DOJO:     
			case EnumJsFramework::JS_FW_YUI:
			case EnumJsFramework::JS_FW_SCRIPTACULOUS:
				include($ajax_root.$group.DIRECTORY_SEPARATOR.$group.".js");
				break;
			case EnumJsFramework::JS_FW_EXTJS:
				if ($version<4){
					$ext_root=$ajax_root."ext".DIRECTORY_SEPARATOR;
				}else{
					$ext_root=$ajax_root."ext4".DIRECTORY_SEPARATOR;
				}
				$js_header=$ext_root.$jsFile;
				include($js_header);
				break;
		}
	}else{
		$url_base=UtilNet::urlbase();
		if (contain($jsFile,$url_base)){
		   $jsFile=str_replace($url_base,"",$jsFile);
		   $jsFile=Gc::$nav_root_path.$jsFile;
		   $jsFile=str_replace("/",DIRECTORY_SEPARATOR,$jsFile);
		}      
		include($jsFile);
	}
}

if (isset ($cssFile)){
	header("Content-type: text/css; charset: UTF-8");   
	if (EnumJsFramework::isEnumValue($group)) {  
		$ajax_root=Gc::$nav_root_path."common".DIRECTORY_SEPARATOR."js".DIRECTORY_SEPARATOR."ajax".DIRECTORY_SEPARATOR;
		$cssFile=str_replace("/", DIRECTORY_SEPARATOR, $cssFile);  
		switch ($group){
			case EnumJsFramework::JS_FW_EXTJS:
				if ($version<4){
					$ext_root=$ajax_root."ext".DIRECTORY_SEPARATOR;
				}else{
					$ext_root=$ajax_root."ext4".DIRECTORY_SEPARATOR;
				}
				include($ext_root.$cssFile);
				break;
		}
	}else{
		$url_base=UtilNet::urlbase();
		if (contain($cssFile,$url_base)){
		   $cssFile=str_replace($url_base,"",$cssFile);
		   $cssFile=Gc::$nav_root_path.$cssFile;           
		   $cssFile=str_replace("/",DIRECTORY_SEPARATOR,$cssFile);
		}
		include($cssFile);
	}
}

if(extension_loaded("zlib")){
	if (Gc::$is_online_optimize){      
		$result=ob_get_clean();                 
		$result=UtilString::online_optimize($result);                                   
		echo $result;
	}else{
		ob_end_flush();//输出buffer中的内容
	}
}
?>