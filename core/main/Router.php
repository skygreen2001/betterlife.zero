<?php
/**
 +-------------------------------------------------<br/>
 * 负责WEB URL的解析<br/>
 * 从用户请求的URL里获取Controller,Action和Parameter。<br/>
 +--------------------------------------------------<br/>
 * @category betterlife
 * @package core.main
 * @author skygreen
 */
class Router 
{
	//<editor-fold defaultstate="collapsed" desc="定义部分">
	//支持的URL模式
	const URL_COMMON=0;   //普通模式
	const URL_PATHINFO=1;   //PATHINFO模式
	const URL_REWRITE=2;   //REWRITE模式
	const URL_COMPAT=3;   // 兼容模式
	//支持的PATHINFO模式
	const URL_PATHINFO_NORMAL=1;   //普通模式
	const URL_PATHINFO_DEFAULT=2;   //智能模式
	/**
	 * PATHINFO 模式,使用数字1、2代表以下三种模式:
	 * 1 普通模式(参数没有顺序,例如/m/module/a/action/id/1);
	 * 2 智能模式(系统默认使用的模式，可自动识别模块和操作/module/action/id/1/ 或者 /module,action,id,1/...);
	 */
	const URL_PATHINFO_MODEL=2;              

	/**
	 * 是否开启URL路由
	 */
	const URL_ROUTER_ON= true;    

	/**
	 *  URL地址是否不区分大小写
	 */
	const URL_CASE_INSENSITIVE  = false;
		
	/**
	 * PATHINFO模式下，各参数之间的分割符号
	 */
	const URL_PATHINFO_DEPR ='/';

	/**
	 *  URL伪静态后缀设置
	 */
	const URL_HTML_SUFFIX='';

	/* 系统变量名称设置 */
	/**
	 * 默认应用获取变量
	 */
	const VAR_GROUP= 'g';
	/**
	 * 默认模块获取变量
	 */
	const VAR_MODULE= 'm';
	/**
	 * 默认操作获取变量
	 */
	const VAR_ACTION= 'a';
	/**
	* 默认导航
	* 规则：
	*   dispatch=betterlife.auth.login
	*   等同于：g=betterlife&m=auth&a=login
	*/
	const VAR_DISPATCH='go';
	/**
	* 默认导航间的间隔点
	*/
	const VAR_DISPATCH_DEPR=".";
	/**
	 *  默认路由获取变量
	 */
	const VAR_ROUTER='r';
	/**
	 * 默认分页跳转变量
	 */
	const VAR_PAGE='p';
	/**
	 * 默认模板切换变量
	 */
	const VAR_TEMPLATE= 't';
	/**
	 * PATHINFO 兼容模式获取变量例如 ?s=/module/action/method/id/1
	 * 后面的参数取决于URL_PATHINFO_MODEL 和 URL_PATHINFO_DEPR
	 */
	const VAR_PATHINFO= 's';
	/**
	 * 模块分组之间的分割符
	 */
	const APP_GROUP_DEPR='.';
	/**
	 * URL校验正常的字符
	 */
	const URL_ALLOWED_URL_CHARS= "/[^A-z0-9\/\=^]/";
	/**
	 * 默认导航的页面，一般为首页
	 */
	const URL_DEFAULT_CONTROLLER="welcome";
	/**
	 * 默认导航的控制器Controller
	 */
	const DEFAULT_MODULE="auth";
	/**
	 * 默认导航的行为方法Action
	 */
	const DEFAULT_ACTION="login";
	/**
	 * URL索引文件名称
	 */
	const URL_INDEX="index.php";
	/**
	 * URL目录间隔符号
	 */
	const URL_SLASH="/";
	/**
	 * URL变量与值之间的连接符号
	 * 示例：a=login
	 */
	const URL_EQUAL="=";
	/**
	 * URL变量与变量之间的连接符号
	 * 示例：m=auth&a=login
	 */
	const URL_CONNECTOR="&";
	/**
	 * URL变量与变量之间的连接符号
	 * 示例：m=auth&a=login
	 */
	const URL_QUESTION="?";

	/**
	 * 额外的参数
	 * 用于
	 *    -Debug
	 *    -验证
	 *    -加密
	 */
	public static $extrasList=array(
			"XDEBUG_SESSION_START"=>1
	);
	/**
	 * @var string 当前文件名
	 */
	private $CURRENT_RUN_FILE;
	/**
	 * @var string WEB应用模块名
	 */
	private $module;
	/**  
	 * @var string Action Controller名称
	 */
	private $controller;
	/**
	* @var string 控制器所在的文件夹路径，同事映射为表示层的文件夹路径。
	* @example:如控制器Action_Library所在目录为system目录下，则tpl文件所在目录为system/library/目录下。
	*/
	private $controller_path;
	/**  
	 * @var string 具体的导航页面名称
	 */
	private $action;
	/**    
	 * @var array 所有的参数
	 */
	private $params;
	/**        
	 * @var array 真正需要的数据
	 */
	private $data;
	/**
	 *
	 * @var string 额外用于验证加密的参数
	 */
	private $extras;
	//</editor-fold>

	public function __construct() {
		if (Gc::$dev_profile_on) Profiler::mark('负责WEB URL的解析');
		$this->init();

		/**
		 * Session初始化
		 */
		if(Gc::$session_auto_start){
		   HttpSession::init();
		}  
		$this->analyzeNavition();
		if (Gc::$dev_profile_on) Profiler::unmark('负责WEB URL的解析');
	}

	public function init() {
		if(!Initializer::$IS_CLI) {
			// 当前文件名
			if(Initializer::$IS_CGI) {
				//CGI/FASTCGI模式下
				$_temp  = explode('.php',$_SERVER["PHP_SELF"]);
				$this->CURRENT_RUN_FILE=rtrim(str_replace($_SERVER["HTTP_HOST"],'',$_temp[0].'.php'),'/');
			}else {
				$this->CURRENT_RUN_FILE=rtrim($_SERVER["SCRIPT_NAME"],'/');
			}
		}
	}

	/**
	 * 解析导航路径
	 */
	private function analyzeNavition() {
		$urlMode=Gc::$url_model;
		if($urlMode == self::URL_REWRITE ) {
			//当前项目地址
			$url=dirname($this->CURRENT_RUN_FILE);
			if($url == '/' || $url == '\\') {
				$url='';
			}
			$this->CURRENT_RUN_FILE=$url;
		}elseif($urlMode == self::URL_COMPAT) {
			$this->CURRENT_RUN_FILE=$this->CURRENT_RUN_FILE.'?'.self::VAR_PATHINFO.'=';
		}

		if($urlMode) {
			$this->url_mcrypt_decode();
			// 获取PATHINFO信息
			self::getPathInfo();
			if (!empty($_GET) && !isset($_GET[self::VAR_ROUTER])) {
				$_GET  =  array_merge (self::parsePathInfo(),$_GET);
			   
				$_varGroup =   self::VAR_GROUP; // 分组变量
				$_varModule =   self::VAR_MODULE;
				$_varAction =  self::VAR_ACTION;
				$_depr  =   self::URL_PATHINFO_DEPR;
				$_pathModel =   self::URL_PATHINFO_MODEL;
				if (empty(Gc::$module_names)) {
					$_GET[$_varGroup] = '';
				}    
				// 设置默认模块和操作
				if(empty($_GET[$_varModule])) $_GET[$_varModule] = self::DEFAULT_MODULE;
				if(empty($_GET[$_varAction])) $_GET[$_varAction] = self::DEFAULT_ACTION;
				// 组装新的URL地址
				$_URL = '/';
				if($_pathModel==self::URL_PATHINFO_DEFAULT) {
					// groupName/modelName/actionName/
					$_URL .= $_GET[$_varGroup].($_GET[$_varGroup]?$_depr:'').$_GET[$_varModule].$_depr.$_GET[$_varAction].$_depr;
					unset($_GET[$_varGroup],$_GET[$_varModule],$_GET[$_varAction]);
				}
				foreach ($_GET as $_VAR => $_VAL) {
					if('' != trim($_GET[$_VAR])) {
						if($_pathModel==self::URL_PATHINFO_DEFAULT) {
							$_URL .= $_VAR.$_depr.rawurlencode($_VAL).$_depr;
						}else {
							$_URL .= $_VAR.'/'.rawurlencode($_VAL).'/';
						}
					}
				}
				if($_depr==',') $_URL = substr($_URL, 0, -1).'/';
//                $this->parsed = parse_url($this->CURRENT_RUN_FILE.$_URL);
				//重定向成规范的URL格式
				Dispatcher::redirect($this->CURRENT_RUN_FILE.$_URL);
			}else {
				if(self::URL_ROUTER_ON) self::routerCheck();   // 检测路由规则
				//给_GET赋值 以保证可以按照正常方式取_GET值
				$_GET = array_merge(self :: parsePathInfo(),$_GET);
				$this->resolveNavDispathParam();
				//保证$_REQUEST正常取值
				$_REQUEST = array_merge($_POST,$_GET);
			}
		}else {
			// 普通URL模式 检查路由规则
			if(isset($_GET[self::VAR_ROUTER])) self::routerCheck();
			$this->url_mcrypt_decode();  
			$this->resolveNavDispathParam();
			$_REQUEST = array_merge($_POST,$_GET);
		}
		if ($_REQUEST){
			$this->setRouteProperties($_REQUEST);
		}
	}
	
	/**
	 * 对加密过的链接地址进行解码 
	 * 加密的url具有以下特征：
	 */
	private function url_mcrypt_decode()
	{   
		if (class_exists("TagHrefClass")&&TagHrefClass::$isMcrypt){     
			if ((count($_GET)==1)){
				$get=each($_GET);
				if (((empty($get["1"]))||($get["1"]=="="))&&(base64_decode($get["0"], true))) {   
					$path = base64_decode($get["0"]); 
					$_GET = UtilNet::parse_urlquery($path);  
				}
			}
		}
	}
	
	/**
	* 支持通过go=admin.index.index的快捷方式进行导航<br/>
	* 参考cs-cart的导航规则进行了改进。
	*/
	private function resolveNavDispathParam()
	{
		if(!empty($_GET[self::VAR_DISPATCH])){
			$_NavSection=explode(self::VAR_DISPATCH_DEPR,$_GET[self::VAR_DISPATCH]);
			$_GET[self::VAR_GROUP] = @$_NavSection[0];   
			$_GET[self::VAR_ACTION] = @end($_NavSection);//@$_NavSection[2];   
			unset($_NavSection[count($_NavSection)-1]);             
			unset($_NavSection[0]);                   
			if (!empty($_NavSection)&&count($_NavSection)>0){
				$_GET[self::VAR_MODULE] = @$_NavSection[count($_NavSection)]; 
				unset($_NavSection[count($_NavSection)]);
			}            
			if (!empty($_NavSection)&&count($_NavSection)>0){
				$this->controller_path= implode(DIRECTORY_SEPARATOR,$_NavSection);//@$_NavSection[1]; 
			}
		}
	}

	/**
	 * 将用户请求中所有带有的参数设置进相应参数
	 * @param array $request
	 */
	private function setRouteProperties($request) {
		$route=$request;
		$this->params=$route;
		/**
		 * 设置模块
		 */
		$var  = self::VAR_GROUP;
		$group= !empty($route[$var])?$route[$var]:Gc::$module_names[2];                          
		if(!empty($route[$var])){
			$group=$route[$var]; 
			unset($route[$var]);
			$this->module=strtolower($group); 
		}else{
		  if (count(Gc::$module_names)>=3){
			$group= Gc::$module_names[2];  
		  }  
		  $this->module=strtolower($group); 
		}  
		/**
		 * 设置控制器
		 */
		$var  =  self::VAR_MODULE;
		$controller = !empty($route[$var])?$route[$var]:self::URL_DEFAULT_CONTROLLER;
		//支持路径的控制器
		if (contain($controller,self::VAR_DISPATCH_DEPR)){            
			$_NavSection=explode(self::VAR_DISPATCH_DEPR,$controller);
			$controller=$_NavSection[count($_NavSection)-1];
			unset($_NavSection[count($_NavSection)-1]);            
			$this->controller_path=implode(DIRECTORY_SEPARATOR,$_NavSection);
		}
		$this->controller =$controller;
		if(self::URL_CASE_INSENSITIVE) {
			// URL地址不区分大小写
			$controller=strtolower($controller);
			// 智能识别方式 index.php/user_type/index/ 识别到 UserTypeAction 模块
			$this->controller = ucfirst(parse_name($controller,1));
		}
		unset($route[$var]);

		/**
		 * 设置action
		 */
		$var  =  self::VAR_ACTION;
		$this->action = !empty($route[$var])?$route[$var]:"execute";
		unset($route[$var]);

		$this->extras=array_intersect_key($route, self::$extrasList);
		$this->data=array_diff_key($route, self::$extrasList);
		$this->data=new DataObjectArray($this->data);
	}
			 
	/**
	 +----------------------------------------------------------
	 * 获得实际的分组名称
	 +----------------------------------------------------------
	 * @access private
	 +----------------------------------------------------------
	 * @return string
	 +----------------------------------------------------------
	 */
	public function getModule() {
		return $this->module;
	}

	public function getController() {
		return $this->controller;
	}
	
	public function getController_path() {
		return $this->controller_path;
	}

	
	public function getAction() {
		return $this->action;
	}

	public function getParams() {
		return $this->params;
	}

	public function getData() {
		return $this->data;
	}

	public function getExtras() {
		return $this->extras;
	}

	/**
	 +----------------------------------------------------------
	 * 路由检测
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @return void
	 +----------------------------------------------------------
	 */
	public static function routerCheck() {
		// 搜索路由映射 把路由名称解析为对应的模块和操作
		$routes = array(
				"welcome"=>array('Auth','login',"id,name,time","rand=10000"),
		);
		if(!empty($routes)) {
			if(isset($_GET[self::VAR_ROUTER])) {
				// 存在路由变量
				$routeName=lcfirst($_GET[self::VAR_ROUTER]);
			}else {
				$paths = explode(self::URL_PATHINFO_DEPR,trim($_SERVER['PATH_INFO'],'/'));
				// 获取路由名称
				$routeName=array_shift($paths);
			}
			if(isset($routes[$routeName])) {
				// 读取当前路由名称的路由规则
				// 路由定义格式 routeName=>array(‘模块名称’,’操作名称’,’参数定义’,’额外参数’)
				$route = $routes[$routeName];
				if(strpos($route[0],self::APP_GROUP_DEPR)) {
					$array   =  explode(self::APP_GROUP_DEPR,$route[0]);
					$_GET[self::VAR_MODULE]= array_pop($array);
					$_GET[self::VAR_GROUP]= implode(self::APP_GROUP_DEPR,$array);
				}else {
					$_GET[self::VAR_MODULE]= $route[0];
				}
				$_GET[self::VAR_ACTION]=$route[1];
				//  获取当前路由参数对应的变量
				if(!isset($_GET[self::VAR_ROUTER])) {
					$vars=explode(',',$route[2]);
					for($i=0;$i<count($vars);$i++)
						$_GET[$vars[$i]]=array_shift($paths);
					// 解析剩余的URL参数
					$res = preg_replace('@(\w+)\/([^,\/]+)@e', '$_GET[\'\\1\']="\\2";', implode('/',$paths));
				}
				if(isset($route[3])) {
					// 路由里面本身包含固定参数 形式为 a=111&b=222
					parse_str($route[3],$params);
					$_GET   =   array_merge($_GET,$params);
				}

			}elseif(isset($routes[$routeName.'@'])) {
				// 存在泛路由
				// 路由定义格式 routeName@=>array(
				// array('路由正则1',‘模块名称’,’操作名称’,’参数定义’,’额外参数’),
				// array('路由正则2',‘模块名称’,’操作名称’,’参数定义’,’额外参数’),
				// ...)
				$routeItem = $routes[$routeName.'@'];
				$regx = str_replace($routeName,'',trim($_SERVER['PATH_INFO'],'/'));
				foreach ($routeItem as $route) {
					$rule    =   $route[0];// 路由正则
					// 匹配路由定义
					if(preg_match($rule,$regx,$matches)) {
						// 检测是否存在分组 2009/06/23
						$temp = explode(self::APP_GROUP_DEPR,$route[1]);
						if ($temp[1]) {
							$_GET[self::VAR_GROUP]  = $temp[0];
							$_GET[self::VAR_MODULE] = $temp[1];
						}else {
							$_GET[self::VAR_MODULE] = $temp[0];
						}
						$_GET[self::VAR_ACTION]  =   $route[2];
						//  获取当前路由参数对应的变量
						if(!isset($_GET[self::VAR_ROUTER])) {
							$vars    =   explode(',',$route[3]);
							for($i=0;$i<count($vars);$i++)
								$_GET[$vars[$i]]     =   $matches[$i+1];
							// 解析剩余的URL参数
							$res = preg_replace('@(\w+)\/([^,\/]+)@e', '$_GET[\'\\1\']="\\2";', str_replace($matches[0],'',$regx));
						}
						if(isset($route[4])) {
							// 路由里面本身包含固定参数 形式为 a=111&b=222
							parse_str($route[4],$params);
							$_GET   =   array_merge($_GET,$params);
						}
						break;
					}
				}
			}

			if(isset($_GET[self::VAR_ROUTER])) {
				// 清除路由变量
				unset($_GET[self::VAR_ROUTER]);
			}
		}
	}

	/**
	 +----------------------------------------------------------
	 * 分析PATH_INFO的参数
	 +----------------------------------------------------------
	 * @access private
	 +----------------------------------------------------------
	 * @return void
	 +----------------------------------------------------------
	 */
	private static function parsePathInfo() {
		$pathInfo = array();
		if(self::URL_PATHINFO_MODEL==2) {
			$paths = explode(self::URL_PATHINFO_DEPR,trim($_SERVER['PATH_INFO'],'/'));
			if (!empty(Gc::$module_names)) {
				$arr = array_map('strtolower',Gc::$module_names);
				$pathInfo[self::VAR_GROUP] = in_array(strtolower($paths[0]),$arr)? array_shift($paths) : '';
			}
			$pathInfo[self::VAR_MODULE] = array_shift($paths);
			$pathInfo[self::VAR_ACTION] = array_shift($paths);
			for($i = 0, $cnt = count($paths); $i <$cnt; $i++) {
				if(isset($paths[$i+1])) {
					$pathInfo[$paths[$i]] = (string)$paths[++$i];
				}elseif($i==0) {
					$pathInfo[$pathInfo[self::VAR_ACTION]] = (string)$paths[$i];
				}
			}
		}else {
			$res = preg_replace('@(\w+)'.self::URL_PATHINFO_DEPR.'([^,\/]+)@e', '$pathInfo[\'\\1\']="\\2";', $_SERVER['PATH_INFO']);
		}
		return $pathInfo;
	}

	/**
	 +----------------------------------------------------------
	 * 获得服务器的PATH_INFO信息
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @return void
	 +----------------------------------------------------------
	 */
	public static function getPathInfo() {
		if(!empty($_GET[self::VAR_PATHINFO])) {
			// 兼容PATHINFO 参数
			$path = $_GET[self::VAR_PATHINFO];
			unset($_GET[self::VAR_PATHINFO]);
		}elseif(!empty($_SERVER['PATH_INFO'])) {
			$pathInfo = $_SERVER['PATH_INFO'];
			if(0 === strpos($pathInfo,$_SERVER['SCRIPT_NAME']))
				$path = substr($pathInfo, strlen($_SERVER['SCRIPT_NAME']));
			else
				$path = $pathInfo;
		}elseif(!empty($_SERVER['ORIG_PATH_INFO'])) {
			$pathInfo = $_SERVER['ORIG_PATH_INFO'];
			if(0 === strpos($pathInfo, $_SERVER['SCRIPT_NAME']))
				$path = substr($pathInfo, strlen($_SERVER['SCRIPT_NAME']));
			else
				$path = $pathInfo;
		}elseif (!empty($_SERVER['REDIRECT_PATH_INFO'])) {
			$path = $_SERVER['REDIRECT_PATH_INFO'];
		}elseif(!empty($_SERVER["REDIRECT_Url"])) {
			$path = $_SERVER["REDIRECT_Url"];
			if(empty($_SERVER['QUERY_STRING']) || $_SERVER['QUERY_STRING'] == $_SERVER["REDIRECT_QUERY_STRING"]) {
				$parsedUrl = parse_url($_SERVER["REQUEST_URI"]);
				if(!empty($parsedUrl['query'])) {
					$_SERVER['QUERY_STRING'] = $parsedUrl['query'];
					parse_str($parsedUrl['query'], $GET);
					$_GET = array_merge($_GET, $GET);
					reset($_GET);
				}else {
					unset($_SERVER['QUERY_STRING']);
				}
				reset($_SERVER);
			}
		}
		if(!empty($path)&&strlen(self::URL_HTML_SUFFIX)>0) {
			$suffix =   substr(self::URL_HTML_SUFFIX,1);
			$path   =   preg_replace('/\.'.$suffix.'$/','',$path);
		}
		$_SERVER['PATH_INFO'] = empty($path) ? '/' : $path;
	}
}
?>
