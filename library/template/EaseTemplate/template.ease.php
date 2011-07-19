<?php
/* 
 * Edition:	ET091001
 * Desc:	ET Template
 * File:	template.php
 * Author:	David Meng
 * Site:	http://www.systn.com
 * Email:	mdchinese@gmail.com
 * 
 */

//错误提示
if (is_file(dirname(__FILE__).'/template.error.php')){
	include dirname(__FILE__).'/template.error.php';
}else{
	define('ET_E_unconnect',	'MemCache Could not connect!');
	define('ET_E_not_exist1',	'Sorry, The file <b>');
	define('ET_E_not_exist2',	'</b> does not exist.');
	define('ET_E_not_exist3',	'<br>Sorry, Error or complicated syntax error exists in ');
	define('ET_E_not_exist4',	' file.');
	define('ET_E_mc_save',		'Failed to save data at the server.');
	define('ET_E_not_write1',	'Sorry,');
	define('ET_E_not_write2',	' file write in failed!');
	define('ET_E_clear_cache',	'Clear Cache');
	define('ET_E_inc_tpl',		'Include Templates (Num:');
	define('ET_E_cache_id',		'Cache File ID');
	define('ET_E_index',		'Index:');
	define('ET_E_format',		'Format:');
	define('ET_E_cache',		'Cache:');
	define('ET_E_template',		'Template:');
	define('ET_E_cache_del',	'Cache file is successfully deleted.');
	define('ET_E_routing',		'Please set template config WebURL adds.<br>Example: $tpl = new template( array( "WebURL" => "http://www.systn.com/" ) );');
	define('ET_E_memcache',		'Please modify PHP.INI Memcache configuration module');
}

//引入核心文件
if (is_file(dirname(__FILE__).'/template.core.php')){
	include dirname(__FILE__).'/template.core.php';
}else {
	die('Sorry. Not load core file.');
}




Class template extends ETCore{
	
	/**
	*	声明模板用法
	*/
	function template(
		$set = array(
				'ID'		 =>'1',					//缓存ID
				'TplType'	 =>'htm',				//模板格式
				'CacheDir'	 =>'cache',				//缓存目录
				'TemplateDir'=>'template' ,			//模板存放目录
				'AutoImage'	 =>'on' ,				//自动解析图片目录开关 on表示开放 off表示关闭
				'LangDir'	 =>'language' ,			//语言文件存放的目录
				'Language'	 =>'default' ,			//语言的默认文件
				'Copyright'	 =>'off' ,				//版权保护
				'MemCache'	 =>'' ,					//Memcache服务器地址例如:127.0.0.1:11211
				'Compress'	 =>'on' ,				//压缩代码
				'WebURL'	 =>'' ,					//如果采用路由模式请设定真实网站地址
			)
		){
		
		parent::ETCoreStart($set);
	}

}
?>