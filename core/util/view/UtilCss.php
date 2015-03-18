<?php
/**
 +--------------------------------<br/>
 * 定义样式<br/>
 +--------------------------------
 * @category betterlife
 * @package util.view
 * @author skygreen
 */
class UtilCss extends Util
{
	/**
	 * 对CSS进行Gzip操作的路径
	 * @var string
	 */
	private static $CSS_GZIP="common/js/gzip.php?css=";
	/**
	 * JS框架名称键名称
	 * @var string
	 */
	private static $CSS_FLAG_GROUP="g";
	/**
	 * JS框架版本键名称
	 * @var type
	 */
	private static $CSS_FLAG_VERSION="v";
	/**
	 * 加载过的css文件。
	 * $value css文件名
	 * @var array
	 */
	public static $CssLoaded=array();

	const CSS_REPORT_TABLE="report";
	/**
	 * 动态加载Ext Required CSS文件
	 * @param ViewObject $viewobject 表示层显示对象
	 * @param string $version javascript框架的版本号
	 */
	public static function loadExt($viewObject=null,$version="3.3.0")
	{
		UtilAjax::init();
		$g_flag_ext=EnumJsFramework::JS_FW_EXTJS;
		$ext_resource_root="resources/css/";
		if ($viewObject){
			self::loadCssReady($viewObject,$ext_resource_root."ext-all.css",true,$g_flag_ext,$version);
			self::loadCssReady($viewObject,$ext_resource_root."xtheme-gray.css",true,$g_flag_ext,$version);
			self::loadCssReady($viewObject,$ext_resource_root."ext-patch.css",true,$g_flag_ext,$version);
			self::loadCssReady($viewObject,"shared/tabscroller/TabScrollerMenu.css",true,$g_flag_ext,$version);
		}else{
			self::loadCss($ext_resource_root."ext-all.css",true,$g_flag_ext,$version);
			self::loadCss($ext_resource_root."xtheme-gray.css",true,$g_flag_ext,$version);
			self::loadCss($ext_resource_root."ext-patch.css",true,$g_flag_ext,$version);
			self::loadCss("shared/tabscroller/TabScrollerMenu.css",true,$g_flag_ext,$version);
		}
	}

	/**
	 * 动态加载CSS文件
	 * @param string $cssFile Css文件名
	 * @param string $cssFlag 分组标识，如都是Ext的Css文件
	 * @param string $version javascript框架的版本号
	 * @param string $charset 字符集
	 */
	public static function loadCss($cssFile,$isGzip=false,$cssFlag=null,$version="",$charset="utf-8")
	{
		echo self::loadCssSentence($cssFile,$isGzip,$cssFlag,$version,$charset);
	}

	/**
	 * 预加载[不直接输出]:动态加载CSS文件
	 * @param ViewObject $viewobject 表示层显示对象
	 * @param string $cssFile Css文件名
	 * @param string $cssFlag 分组标识，如都是Ext的Css文件
	 * @param string $version javascript框架的版本号
	 * @param string $charset 字符集
	 */
	public static function loadCssReady($viewobject,$cssFile,$isGzip=false,$cssFlag=null,$version="",$charset="utf-8")
	{
		if ($viewobject instanceof ViewObject){
			if (!isset($viewobject->css_ready)||empty($viewobject->css_ready)){
				$viewobject->css_ready="";
			}
			$viewobject->css_ready.=self::loadCssSentence($cssFile,$isGzip,$cssFlag,$version,$charset);
		}
	}

	/**
	 * 动态加载CSS文件的语句
	 * @param string $cssFile Css文件名
	 * @param string $cssFlag 分组标识，如都是Ext的Css文件
	 * @param string $version javascript框架的版本号
	 * @param string $charset 字符集
	 */
	public static function loadCssSentence($cssFile,$isGzip=false,$cssFlag=null,$version="",$charset="utf-8")
	{
		$result="";
		if (isset($cssFile)){
			$url_base=UtilNet::urlbase();
			if ($isGzip){
				if (isset($cssFlag)){
					$cssFile.="&".self::$CSS_FLAG_GROUP."=".$cssFlag;
				}
				if (!empty($version)){
					$cssFile.="&".self::$CSS_FLAG_VERSION."=".$version;
				}
				if (in_array($cssFile, self::$CssLoaded)){
					return ;
				}
				$css_gzip=self::$CSS_GZIP;
				if (contain($cssFile,Gc::$url_base)){
					$file_sub_dir=str_replace("/", DS, dirname($_SERVER["SCRIPT_FILENAME"])).DS;
					if(contain($file_sub_dir,Gc::$nav_root_path)){
						$isLocalCssFile=str_replace(Gc::$url_base,Gc::$nav_root_path,$cssFile);
					}else{
						$isLocalCssFile=str_replace(Gc::$url_base,$file_sub_dir,$cssFile);
					}

					if (is_server_windows()){
						$isLocalCssFile=str_replace("/","\\",$isLocalCssFile);
					}
					if (contain($isLocalCssFile,"resources".DIRECTORY_SEPARATOR."css")){
						$isLocalCssFile=substr($isLocalCssFile,0,strpos($isLocalCssFile,"resources".DIRECTORY_SEPARATOR."css")+9);
					}
					$isLocalGzip=$isLocalCssFile.DIRECTORY_SEPARATOR."gzip.php";
					if (file_exists($isLocalGzip)){
						if(contain($file_sub_dir,Gc::$nav_root_path)){
							$css_gzip=str_replace(Gc::$nav_root_path,"",$isLocalGzip)."?css=";
						}else{
							$css_gzip=str_replace($_SERVER["DOCUMENT_ROOT"]."/","",$isLocalGzip)."?css=";
						}
						$css_gzip=str_replace("\\","/",$css_gzip);
					}
				}else{
					if (contain(strtolower(php_uname()),"darwin")){
						$file_sub_dir=str_replace("/", DS, dirname($_SERVER["SCRIPT_FILENAME"])).DS;
						$css_gzip=str_replace($_SERVER["DOCUMENT_ROOT"]."/", "", $file_sub_dir).$css_gzip;
						$start_str=substr($css_gzip, 0,strpos($css_gzip, "/"));
						$url_basei=substr($url_base, 0,strlen($url_base)-1);
						$end_str=substr($url_basei,strrpos($url_basei, "/")+1);
						if($start_str==$end_str)$css_gzip=str_replace($end_str."/","",$css_gzip);
					}
				}
				$result= "	 <link rel=\"stylesheet\" type=\"text/css\" href=\"".$url_base.$css_gzip.$cssFile."\" />\r\n";
			}else{
				if (in_array($cssFile, self::$CssLoaded)){
					return ;
				}
				if (startWith($cssFile, "http")){
					$result= "	 <link rel=\"stylesheet\" type=\"text/css\" href=\"".$cssFile."\" />\r\n";
				}else{
					if (contain(strtolower(php_uname()),"darwin")){
						$file_sub_dir=str_replace("/", DS, dirname($_SERVER["SCRIPT_FILENAME"])).DS;
						$cssFile=str_replace($_SERVER["DOCUMENT_ROOT"]."/", "", $file_sub_dir).$cssFile;

						$start_str=substr($cssFile, 0,strpos($cssFile, "/"));
						$url_basei=substr($url_base, 0,strlen($url_base)-1);
						$end_str=substr($url_basei,strrpos($url_basei, "/")+1);
						if($start_str==$end_str)$cssFile=str_replace($end_str."/","",$cssFile);
					}
					$result= "	 <link rel=\"stylesheet\" type=\"text/css\" href=\"".$url_base.$cssFile."\" />\r\n";
				}
			}
			self::$CssLoaded[]=$cssFile;
		}
		return $result;
	}

	/**
	 * 动态加载应用指定的Css内容的语句。
	 * @param string $cssContent：Css内容的语句
	 */
	public static function loadCssContent($cssContent)
	{
		echo self::loadCssContentSentence($cssContent);
	}

	/**
	 * 预加载[不直接输出]:动态加载应用指定的Css内容的语句。
	 * @param ViewObject $viewobject 表示层显示对象,只在Web框架中使用,一般结合loadJsReady使用
	 * @param string $cssContent：Js内容的语句
	 */
	public static function loadCssContentReady($viewobject,$cssContent)
	{
		if ($viewobject instanceof ViewObject){
			if (!isset($viewobject->css_ready)||empty($viewobject->css_ready)){
				$viewobject->css_ready="";
			}
			$viewobject->css_ready.=self::loadCssContentSentence($cssContent);
		}
	}

	/**
	 * 动态加载应用指定的Js内容的语句。
	 * @param string $cssContent：Css内容的语句
	 */
	public static function loadCssContentSentence($cssContent)
	{
		$result="	 <style type=\"text/css\">\r\n";
		$result.="		".$cssContent."\r\n";
		$result.="	 </style>\r\n";
		return $result;
	}

	/**
	 * 列表信息样式报表
	 * @see http://www.somacon.com/p141.php
	 * {self::CSS_REPORT_TABLE}
	 */
	public static function report_info()
	{
		echo '<style type="text/css">
			table.'.self::CSS_REPORT_TABLE.' {
					border-width: 0px;
					border-spacing: ;
					border-style: groove;
					border-color: ;
					border-collapse: separate;
					background-color: white;
			}
			table.'.self::CSS_REPORT_TABLE.' th {
					border-width: 1px;
					padding: 1px;
					border-style: solid;
					border-color: green;
					background-color: #fff5ee;
					-moz-border-radius: ;
			}
			table.'.self::CSS_REPORT_TABLE.' td {
					border-width: 1px;
					padding: 1px;
					border-style: solid;
					border-color: green;
					background-color: #fff5ee;
					-moz-border-radius: ;
			}
			</style>';
	}

	/**
	 * 定义form的样式
	 */
	public static function form_css()
	{
		$showResult = '<style type="text/css">
				html,body {
					font:normal 15px SimSun,sans-serif;
					border:0 none;
					overflow:auto;
					line-height:1.5em;
					margin:5px 0 0;
					padding:0;
				}
				div {
					margin:15px;
				}
				h1 {
					font:bold 150% SimSun,sans-serif,STXingkai;
				}
				p#indexPage {
					line-height:2em;
					width:500px;
					padding-left:30px;
					text-align:left;
				}

				a {
					color: #1E4176;
				}
				a:link {
					color: #1E4176;
					text-decoration: none;
				}

				a:visited {
					color: #555;
					text-decoration: none;
				}

				a:hover {
					text-decoration: underline;
					color: #15428b;
				}
				label {
					vertical-align:middle;
					width:150px;
					height:35px;
					text-align:right;
					margin:2px 4px 6px;
				}
				input[type=text],input[type=password]{
					border:1px solid #FFF;
					text-align:center;
					width:200px;
					height:28px;
					line-height:28px;
					color:white;
					background:gray;

					margin:10px 0px 0px 0px;
					font-size: 14px;
					color: #555;
					vertical-align: middle;
					background-color: #fff;
					border: 1px solid #ccc;
					border-radius: 4px;
					-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
					box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
					-webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
					transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
				}
				select{
					width:410px;
					text-align:left;
					padding:6px 0px 4px 10px;
					font-size:14px;
					height:28px;
					line-height:28px;
					vertical-align:bottom;
	 				box-sizing: content-box;
	 				-moz-box-sizing:content-box;
	 				-webkit-box-sizing:content-box;


					margin:10px 0px 0px 0px;
					font-size: 14px;
					color: #555;
					vertical-align: middle;
					background-color: #fff;
					border: 1px solid #ccc;
					border-radius: 4px;
					-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
					box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
					-webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
					transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
				}
				input[type=button]{
					border:1px solid;
					text-align:center;
					width:80px;
					height:28px;
				}
				input:hover {
					border:1px solid green;
					color:#000;
					background:#FFF;
				}

				input[type=submit] {
					width:100px;
					height:32px;
					color:#FFF;
					cursor:pointer;
					font:bold 84% SimSun,helvetica,sans-serif;
					background-color:#000;
					border:1px solid;
					border-color:#696 #363 #363 #696;
				}

				input[type=submit]:hover{
					color:#000;
					background-color:#FFF;
				}
			</style>';
		return $showResult;
	}

}

?>
