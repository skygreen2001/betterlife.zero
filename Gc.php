<?php
//<editor-fold defaultstate="collapsed" desc="枚举类型">
//加载枚举类型定义
class_exists("Enum")||require(dirname(__FILE__)."/core/Enum.php");
/**
 * 日志记录方式
 */
class EnumLogType extends Enum{
	/**
	 * 默认。根据在 php.ini 文件中的 error_log 配置，错误被发送到服务器日志系统或文件。
	 */
	const SYSTEM	= 0;
	/**
	 * 日志通过邮件发送
	 */
	const MAIL	  = 1;
	/**
	 * 通过 PHP debugging 连接来发送错误,在PHP3以后就不再使用了
	 */
	const DEGUG	 = 2;
	/**
	 * 错误发送到文件目标字符串
	 */
	const FILE	  = 3;
	/**
	 * SAPI:Server Application Programming Interface 服务端应用编程端口.
	 */
	const SAPI	  = 4;
	/**
	 * 浏览器显示。
	 */
	const BROWSER	= 11;
	/**
	 * 默认记录在数据库中
	 */
	const DB		= 100;
	/**
	 * 通过Firebug Console 输出。
	 */
	const FIREBUG   = 101;
}
//</editor-fold>

/**
 +-----------------------------------<br/>
 * 定义全局使用变量<br/>
 +------------------------------------

 * @access public
 */
class Gc
{
	//<editor-fold desc="网站使用设置">
	/**
	 * 是否在线性能优化
	 * @var mixed
	 */
	public static $is_online_optimize=false;
	/**
	 * 网站应用的名称<br/>
	 * 展示给网站用户
	 * @var string
	 * @static
	 */
	public static $site_name="Betterlife CMS网站框架";
	/**
	 * 网站应用的版本
	 */
	public static $version="1.0";
	/**
	 * 网站根路径的URL路径
	 * @var string
	 * @static
	 */
	public static $url_base;//="http://localhost/betterlife/";//获取网站URL根路径
	/**
	 * 网站根路径的物理路径
	 * @var string
	 * @static
	 */
	public static $nav_root_path;//="C:\\wamp\\www\\betterlife\\";
	/**
	 * 框架文件所在的路径 <br/>
	 * 有两种策略可以部署<br/>
	 * 1.框架和应用整合在一起；则路径同$nav_root_path   <br/>
	 * 2.框架和应用分开，在php.ini里设置include_path="";添加框架所在的路径<br/>
	 *				   则可以直接通过  <br/>
	 * @var string
	 * @static
	 */
	public static $nav_framework_path;//="C:\\wamp\\www\\betterlife\\";
	/**
	* 上传或者下载文件的路径
	*
	* @var mixed
	*/
	public static $attachment_path;//="C:\\wamp\\www\\betterlife\\attachment\\";
	/**
	* 上传或者下载文件的网络路径[可删除]
	*
	* @var mixed
	*/
	public static $attachment_url;//="http://localhost/betterlife/attachment/";
	/**
	* 上传图片的网络路径
	*
	* @var mixed
	*/
	public static $upload_url;//="http://localhost/betterlife/upload/";
	/**
	* 上传图片的路径
	*
	* @var mixed
	*/
	public static $upload_path;//="C:\\wamp\\www\\betterlife\\upload\\";
	//</editor-fold>

	//<editor-fold desc="开发者使用设置">
	/**
	 * 网站应用的名称<br/>
	 * 在网站运行程序中使用，不展示给网站用户；如标识日志文件的默认名称等等。
	 * @var string
	 * @static
	 */
	public static $appName="betterlife";
	public static $appName_alias="Bb";//应用名的缩写，主要用在后台Extjs生成代码的命名空间缩写
	/**
	 * 业务应用部署的根目录<br/>
	 * 说明：该框架采用模块组建的方式<br/>
	 * 每一个遵循该框架的网站业务应用都部署在该目录下<br/>
	 * @var string
	 * @static
	 */
	public static $module_root="home";
	/**
	 * 开发者可在同一个web里部署多个业务应用
	 * $moduleName里定义的是每个业务应用所在的根目录路径
	 * @var array
	 * @static
	 */
	public static $module_names=array(
			"admin",
			"betterlife",
			"model",
			//"business",
	);
	/**
	 * 所有无需预加载的 业务应用所在的根目录路径下的子目录<br/>
	 * 举例：<br/>
	 *	  view在module目录下<br/>
	 *	  主要为html,javascript,css文件；不是类对象文件，因此无需预加载
	 * @var array
	 * @static
	 */
	public static $moudle_exclude_subpackage=array(
			"view",
	);
	/**
	 * URL访问模式,可选参数0、1、2、3,代表以下四种模式：<br/>
	 * 0 (普通模式);<br/>
	 * 1 (PATHINFO 模式); eg:<br/>
	 * 2 (REWRITE  模式); 需要打开.htaccess里的注释行: RewriteEngine On;
     *                    eg: http://localhost/betterlife/betterlife/auth/login<br/>
	 * 3 兼容模式(通过一个GET变量将PATHINFO传递给dispather，默认为s index.php?s=/module/action/id/1)<br/>
	 * 当URL_DISPATCH_ON开启后有效; 默认为PATHINFO 模式，提供最好的用户体验和SEO支持
	 * @var int
	 * @static
	 */
	public static $url_model=0;

	/**
	 * 是否打开Debug模式
	 * @var bool
	 * @static
	 */
	public static $dev_debug_on=false;

	/**
	 * 是否打开Smarty Debug Console窗口
	 * @var bool
	 * @static
	 */
	public static $dev_smarty_on=false;

	/**
	 * 是否要Profile网站性能
	 * @var bool
	 * @static
	 */
	public static $dev_profile_on=false;
	/**
	 * 模板模式<br/>
	 * 本框架自带四种开源模板支持<br/>
	 * 1:Smarty<br/>
	 * 2:SmartTemplate<br/>
	 * 3:EaseTemplate<br/>
	 * 4:TemplateLite<br/>
	 * 5.Flexy【HTML_Template_Flexy】<br/>
	 * 0:不支持任何模板<br/>
	 * 默认在这里指定支持其中一种；若在开发中需要用到多种模板技术；<br/>
	 * 需在使用时通过View进行指定使用<br/>
	 * 当模板为1:Smarty时，模板的标签写法参考/home/betterlife/view/default/core/blog/display.tpl
	 * @var int
	 * @static
	 */
	public static $template_mode=1;
	/**
	 * 每个模块可以定义自己的模板模式
	 * 如过没有定义，则使用$template_mode默认定义的名称，一般都是1:Smarty
	 * @var mixed
	 */
	public static $template_mode_every=array(
		//'betterlife'=>5
	);
	/**
	 * 开发者自定义当前使用模板目录名<br/>
	 * 示例：D:\wamp\www\betterlife\home\betterlife\view\default<br/>
	 *	   default即自定义当前使用模板目录名
	 * @var string
	 * @static
	 */
	public static $self_theme_dir="default";
	/**
	 * 每个模块可以定义自己显示的模板名
	 * 如果没有定义，则使用$self_theme_dir默认定义的名称，一般都是default
	 * @var mixed
	 */
	public static $self_theme_dir_every=array(
		//'betterlife'=>'flexy'
	);
	/**
	 * 是否与Ucenter的用户中心进行整合
	 * @var mixed
	 */
	public static $is_ucenter_integration=false;
	/**
	* 模板文件后缀名称<br/>
	* 一般为.tpl,.php,.html,.htm;<br/>
	* 一般不支持开源模板的时候，使用.php后缀名；即仍可以使用php语法；但不利于逻辑层和页面设计html代码分离<br/>
	* 模板文件一般通用tpl后缀；也有开源模板通常使用html或者htm后缀名；实际上后缀名为任何名称都可以<br/>
	* @var string
	* @static
	*/
	public static $template_file_suffix=".tpl";
	/**
	 * @string 页面字符集<br/>
	 * 一般分为：<br/>
	 * UTF-8<br/>
	 * GBK<br/>
	 * 最终可以由用户选择
	 * @var string
	 * @static
	 */
	public static $encoding="UTF-8";
	/**
	 * @var string 文字显示默认语言
	 * @static
	 * 最终可以由用户选择
	 */
	public static $language="Zh_Cn";
	/**
	 * 是否Session自动启动
	 * @var bool
	 * @static
	 */
	public static $session_auto_start=true;
	/**
	* 单点登录认证框架:oauth
	* -oauth
	* -openid
	* @var mixed
	*/
	public static $sso_method="oauth";
	/**
	 * 通常用于用邮件发送重要日志给管理员。
	 * @var array 邮件的配置。
	 * @static
	 */
	//<editor-fold defaultstate="collapsed" desc="邮件的设置">
	public static $email_config=array(
		"SMTP"=>"smtp.sina.com.cn",
		'smtp_port'=>"25",
		"sendmail_from"=>"skygreen2001@sina.com",
		/**
		 * 可在php.ini中设置sendmail_path，无法通过ini_set实时设置，因为它只能在php.ini或者httpd.conf中设置。<br/>
		 * 因为官网文档【http://php.net/manual/en/mail.configuration.php】：sendmail_path "/usr/sbin/sendmail -t -i" PHP_INI_SYSTEM
		 */
		//"sendmail_path"=>"C:\wamp\sendmail\sendmail.exe -t",
		/**
		 * 通过邮件发送日志的目的者
		 */
		"mailto"=>"skygreen@sohu.com"
	);
	//</editor-fold>

	/**
	 * 日志的配置。
	 * @var array 日志的配置。
	 * @static
	 */
	//<editor-fold defaultstate="collapsed" desc="日志的设置">
	public static $log_config=array(
		/**
		 * 默认日志记录的方式。<br/>
		 * 一般来讲，日志都通过log记录，由本配置决定它在哪里打印出来。<br/>
		 * 可通过邮件发送重要日志，可在数据库或者文件中记录日志。也可以通过Firebug显示日志。
		 */
		"logType"=>EnumLogType::FILE,
		/**
		 * 允许记录的日志级别
		 */
		"log_record_level"=> array('EMERG','ALERT','CRIT','ERR','INFO'),
		/**
		 * 日志文件路径<br/>
		 * 可指定日志文件放置的路径<br/>
		 * 如果为空不设置，则在网站应用根目录下新建一个log目录，放置在它下面
		 */
		"logpath"=>'',
		/**
		 * 检测日志文件大小，超过配置大小则备份日志文件重新生成，单位为字节
		 */
		"log_file_size"=>1024000000,
		/**
		 * 日志记录的时间格式
		 */
		"timeFormat" =>  '%Y-%m-%d %H:%M:%S',
		/**
		 * 通过邮件发送日志的配置。
		 */
		"config_mail_log"=>array(
			'subject' => '重要的日志事件',
			'mailBackend' => '',
		),
		"log_table"=>"bb_log_log",
	);
	//</editor-fold>
	//</editor-fold>

	/**
	* 无需配置自动注入网站的网络地址和物理地址。
	*/
	//<editor-fold defaultstate="collapsed" desc="初始化设置">
	public static function init()
	{
		if (empty(Gc::$nav_root_path)){
			Gc::$nav_root_path=dirname(__FILE__).DIRECTORY_SEPARATOR;
			Gc::$attachment_path=Gc::$nav_root_path."attachment".DIRECTORY_SEPARATOR;
			Gc::$upload_path=Gc::$nav_root_path."upload".DIRECTORY_SEPARATOR;
		}

		if (empty(Gc::$nav_framework_path)){
			Gc::$nav_framework_path=dirname(__FILE__).DIRECTORY_SEPARATOR;
		}

		if (empty(Gc::$url_base)){
			if(isset($_SERVER['HTTPS']) && strpos('on',$_SERVER['HTTPS'])){
				$baseurl = 'https://'.$_SERVER['HTTP_HOST'];
				if($_SERVER['SERVER_PORT']!=443)$baseurl.=':'.$_SERVER['SERVER_PORT'];
			}else{
				$baseurl = 'http://'.$_SERVER['HTTP_HOST'];
				if (!(strpos($_SERVER['HTTP_HOST'],$_SERVER['SERVER_PORT'])!== false)){
					if($_SERVER['SERVER_PORT']!=80)$baseurl.=':'.$_SERVER['SERVER_PORT'];
				}
			}
			$baseDir = dirname($_SERVER['SCRIPT_NAME']);
			$baseurl.=($baseDir == '\\' ? '' : $baseDir);
			if (strpos(strrev($baseurl), "/") !== 0)$baseurl.='/';
			Gc::$url_base=$baseurl;
			$with_file=$_SERVER["SCRIPT_FILENAME"];
			$file_sub_dir=dirname($with_file).DIRECTORY_SEPARATOR;
			$file_sub_dir=str_replace("/", DIRECTORY_SEPARATOR, $file_sub_dir);
			$file_sub_dir=str_replace(Gc::$nav_root_path, "", $file_sub_dir);
			$file_sub_dir=str_replace(DIRECTORY_SEPARATOR, "/", $file_sub_dir);
			Gc::$url_base=str_replace($file_sub_dir, "", Gc::$url_base);
			if (empty(Gc::$attachment_url)){
				Gc::$attachment_url=Gc::$url_base;
				$same_part=explode(DIRECTORY_SEPARATOR,Gc::$nav_root_path);
				if ($same_part&&(count($same_part)>2)){
					$same_part=$same_part[count($same_part)-2];
					if (strpos(strtolower(Gc::$attachment_url),"/".strtolower($same_part)."/")!== false) {
						Gc::$attachment_url=substr(Gc::$attachment_url,0,(strrpos(Gc::$attachment_url,$same_part."/")+strlen($same_part)+1))."attachment/";
					}else{
						$parse_url=parse_url(Gc::$attachment_url);
						Gc::$attachment_url=$parse_url["scheme"]."://".$parse_url["host"];
						if (!empty($parse_url["port"]))  Gc::$attachment_url.=":".$parse_url["port"];
						Gc::$attachment_url.="/attachment/";
					}
				}
			}
			if (empty(Gc::$upload_url)){
				Gc::$upload_url=Gc::$url_base;
				$same_part=explode(DIRECTORY_SEPARATOR,Gc::$nav_root_path);
				if ($same_part&&(count($same_part)>2)){
					$same_part=$same_part[count($same_part)-2];
					if (strpos(strtolower(Gc::$upload_url),"/".strtolower($same_part)."/")!== false) {
						Gc::$upload_url=substr(Gc::$upload_url,0,(strrpos(Gc::$upload_url,$same_part."/")+strlen($same_part)+1))."upload/";
					}else{
						$parse_url=parse_url(Gc::$upload_url);
						Gc::$upload_url=$parse_url["scheme"]."://".$parse_url["host"];
						if (!empty($parse_url["port"]))  Gc::$upload_url.=":".$parse_url["port"];
						Gc::$upload_url.="/upload/";
					}
				}
			}
		}
	}
	//</editor-fold>
}

//<editor-fold defaultstate="collapsed" desc="邮件在php.ini里全局的设置">
ini_set("SMTP", Gc::$email_config["SMTP"]);
ini_set("smtp_port", Gc::$email_config["smtp_port"]);
ini_set('sendmail_from', Gc::$email_config['sendmail_from']);
//</editor-fold>
Gc::init();
?>
