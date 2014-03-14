<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: uc.php 10988 2009-01-19 05:44:31Z zhengqingpeng $
*/
//define('IN_BETTERLIFE', TRUE);
define('UC_CLIENT_VERSION', '1.6.0');    //UCenter 版本标识
define('UC_CLIENT_RELEASE', '20110501');

define('API_DELETEUSER', 1);        //用户删除 API 接口开关
define('API_RENAMEUSER', 1);        //用户改名 API 接口开关
define('API_GETTAG', 1);        //获取标签 API 接口开关
define('API_SYNLOGIN', 1);        //同步登录 API 接口开关
define('API_SYNLOGOUT', 1);        //同步登出 API 接口开关
define('API_UPDATEPW', 1);        //更改用户密码 开关
define('API_UPDATEBADWORDS', 1);    //更新关键字列表 开关
define('API_UPDATEHOSTS', 1);        //更新域名解析缓存 开关
define('API_UPDATEAPPS', 1);        //更新应用列表 开关
define('API_UPDATECLIENT', 1);        //更新客户端缓存 开关
define('API_UPDATECREDIT', 1);        //更新用户积分 开关
define('API_GETCREDITSETTINGS', 1);    //向 UCenter 提供积分设置 开关
define('API_GETCREDIT', 1);        //获取用户的某项积分 开关
define('API_UPDATECREDITSETTINGS', 1);    //更新应用积分设置 开关
//define('API_ADDFEED', 1);    //向 Betterlife 添加feed 开关

define('API_RETURN_SUCCEED', '1');
define('API_RETURN_FAILED', '-1');
define('API_RETURN_FORBIDDEN', '-2');

define('DISCUZ_ROOT', '..'.DIRECTORY_SEPARATOR);
define('S_ROOT', substr(dirname(__FILE__), 0, -3));

$_SGLOBAL = array();

//获取时间
$_SGLOBAL['timestamp'] = time();

/*if(defined('IN_UC')) 
{
	global $_SGLOBAL;
	include_once S_ROOT.'.'.DIRECTORY_SEPARATOR.'api'.DIRECTORY_SEPARATOR.'config.inc.php';
	include_once S_ROOT.'.'.DIRECTORY_SEPARATOR."init.php";
	//链接数据库
	include_once(S_ROOT.'.'.DIRECTORY_SEPARATOR.'api'.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'db_mysql.class.php');
	if(empty($_SGLOBAL['db'])) {
		$_SGLOBAL['db'] = new dbstuff;
		$_SGLOBAL['db']->charset = Gc::$encoding;
		$_SGLOBAL['db']->connect(Config_Mysql::connctionurl(),Config_Mysql::$username,Config_Db::$password,Config_Db::$dbname,Config_Db::$is_persistent);
	}
} 
else 
{*/
	error_reporting(0);
	set_magic_quotes_runtime(0);
	defined('MAGIC_QUOTES_GPC') || define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
	include_once S_ROOT.'.'.DIRECTORY_SEPARATOR.'api'.DIRECTORY_SEPARATOR.'config.inc.php';
	include_once S_ROOT.'.'.DIRECTORY_SEPARATOR."init.php";
	$get = $post = array();
	$code = @$_GET['code'];
	parse_str(authcode($code, 'DECODE', UC_KEY), $get);
	if(MAGIC_QUOTES_GPC) {
		$get = sstripslashes($get);
	}

	if($_SGLOBAL['timestamp'] - $get['time'] > 3600) {
		exit('Authracation has expiried');
	}
	if(empty($get)) {
		exit('Invalid Request');
	}

	include_once S_ROOT.'.'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'uc_client'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'xml.class.php';
	$post = xml_unserialize(file_get_contents('php://input'));

	if(in_array($get['action'], array('test', 'deleteuser', 'renameuser', 'gettag', 'synlogin', 'synlogout', 'updatepw', 'updatebadwords', 'updatehosts', 'updateapps', 'updateclient', 'updatecredit', 'getcredit', 'getcreditsettings', 'updatecreditsettings', 'addfeed'))) {
		$ucApp = new UcApp();
		echo $ucApp->$get['action']($get, $post);
		exit();
	} else {
		exit(API_RETURN_FAILED);
	}
//}

class UcApp 
{
	private $db = '';
	private $tablepre = '';
	private $appdir = '';

	private function _serialize($arr, $htmlon = 0) {
		if(!function_exists('xml_serialize')) {
			include_once S_ROOT.'.'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'uc_client'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'xml.class.php';
		}
		return xml_serialize($arr, $htmlon);
	}

	private function _unserialize($s) {
		if(!function_exists('xml_unserialize')) {
			include_once S_ROOT.'.'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'uc_client'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'xml.class.php';
		}
		return xml_unserialize($s);
	}

	public function UcApp() {
		global $_SGLOBAL;
		$this->appdir = substr(dirname(__FILE__), 0, -3);
		$this->db = $_SGLOBAL['db'];
		$this->tablepre = Config_Mysql::$table_prefix;
	}

	public function test($get, $post) 
	{
		return API_RETURN_SUCCEED;
	}
	
	/**
	 * 如果应用程序需要和其他应用程序进行同步登录，此部分代码负责标记指定用户的登录状态。
	 * 输入的参数放在 $get['uid'] 中，值为用户 ID。此接口为通知接口，无输出内容。同步登录需使用 P3P 标准。
	 * @param mixed $get
	 * @param mixed $post
	 */
	public function synlogin($get, $post) 
	{
		global $_SGLOBAL;
		
		if(!API_SYNLOGIN) {
			return API_RETURN_FORBIDDEN;
		}
	
		//note 同步登录 API 接口
		ob_clean();
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
	
		$cookietime = 31536000;
		$uid = intval($get['uid']);
		$user=User::get_by_id($uid);        
		/**
		 * Session初始化
		 */
		if(Gc::$session_auto_start){
		   HttpSession::init();
		}  
		if($user) {
			HttpSession::set('user_id',$uid);
			//设置cookie
			ssetcookie('auth', authcode($user->password."\t".$user->getId(), 'ENCODE'), $cookietime);
		}
		ssetcookie('loginuser', $get['username'], $cookietime);
	}
	
	/**
	 * 如果应用程序需要和其他应用程序进行同步退出登录，此部分代码负责撤销用户的登录的状态。
	 * 此接口为通知接口，无输入参数和输出内容。同步退出需使用 P3P 标准。
	 * @param mixed $get
	 * @param mixed $post
	 */
	public function synlogout($get, $post) 
	{
		global $_SGLOBAL;
		
		if(!API_SYNLOGOUT) {
			return API_RETURN_FORBIDDEN;
		}        
		/**
		 * Session初始化
		 */
		if(Gc::$session_auto_start){
		   HttpSession::init();
		}  
		HttpSession::remove("user_id");	
		//note 同步登出 API 接口
		ob_clean();
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		clearcookie();
	}
	
	public function updatepw($get, $post) 
	{
		global $_SGLOBAL;
		
		if(!API_UPDATEPW) {
			return API_RETURN_FORBIDDEN;
		}
	
		$username = $get['username'];
		$newpw = md5(time().rand(100000, 999999));
		
		$user=User::get_one(array('username'=>$username));
		if ($user){
			$user->password=md5($newpw);
			$user->update();
		}
		return API_RETURN_SUCCEED;
	}
}

/**
 * 字符串解密加密
 * @param mixed $string
 * @param mixed $operation
 * @param string $key
 * @param mixed $expiry
 * @return string
 */
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) 
{

	$ckey_length = 4;    // 随机密钥长度 取值 0-32;
				// 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
				// 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
				// 当此值为 0 时，则不产生随机密钥

	$key = md5($key ? $key : UC_KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}
}

/**
 * 去掉slassh
 * @param string $string
 * @return string
 */
function sstripslashes($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = sstripslashes($val);
		}
	} else {
		$string = stripslashes($string);
	}
	return $string;
}

/**
 *  SQL ADDSLASHES
 * @param string $string
 * @return string
 */
function saddslashes($string) 
{
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = saddslashes($val);
		}
	} else {
		$string = addslashes($string);
	}
	return $string;
}

/**
 * cookie设置
 * @param mixed $var
 * @param mixed $value
 * @param mixed $life
 */
function ssetcookie($var, $value, $life=0) 
{
	global $_SGLOBAL, $_SC, $_SERVER;
	$_SC['cookiepre']         = 'uchome_'; //COOKIE前缀
	$_SC['cookiedomain']     = ''; //COOKIE作用域
	$_SC['cookiepath']         = '/'; //COOKIE作用路径
	setcookie($_SC['cookiepre'].$var, $value, $life?($_SGLOBAL['timestamp']+$life):0, $_SC['cookiepath'], $_SC['cookiedomain'], $_SERVER['SERVER_PORT']==443?1:0);
}
/**
 * 清空cookie
 */
function clearcookie() 
{
	global $_SGLOBAL;
	ob_clean();
	ssetcookie('auth', '', -86400 * 365);
	$_SGLOBAL['supe_uid'] = 0;
	$_SGLOBAL['supe_username'] = '';
	$_SGLOBAL['member'] = array();
}