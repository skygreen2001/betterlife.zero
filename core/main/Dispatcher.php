<?php
/**
 +--------------------------------------------------<br/>
 * 负责WEB URL的转发<br/>
 +--------------------------------------------------<br/>
 * @category betterlife
 * @package core.main
 * @author skygreen
 */
class Dispatcher 
{
	/**
	 * 是否输出返回静态页面信息
	 * @var bool
	 */
	public static $isOutputStatic=false;
	/**
	 * WEB URL的转发
	 * @global Action $app
	 * @param Router $router
	 */
	public static function dispatch($router) 
	{
		if (Gc::$dev_profile_on) Profiler::mark(Wl::LOG_INFO_PROFILE_WEBURL);
		$isValidRequet=false;
		$controller = $router->getController();
		if ($controller==Router::URL_DEFAULT_CONTROLLER){
		  include_once(Gc::$nav_root_path.Router::URL_DEFAULT_CONTROLLER.Config_F::SUFFIX_FILE_PHP);
		  return;
		}       
		$moduleName=$router->getModule();   
		if (array_key_exists($moduleName,Initializer::$moduleFiles)) {      
		  $moduleFile=Initializer::$moduleFiles[$moduleName];
		} else {
		  include_once(Gc::$nav_root_path.Router::URL_DEFAULT_CONTROLLER.Config_F::SUFFIX_FILE_PHP);
		  return;
		}
		//foreach (Initializer::$moduleFiles as $moduleName=>$moduleFile) {
		$action_controller=Action::ROUTINE_CLASS_PREFIX.ucfirst($controller);
		if (array_key_exists($action_controller,$moduleFile)) {
			require_once($moduleFile[$action_controller]);
			/**
			 * 当前运行的控制器Action Controller
			 */
			$current_action = new $action_controller($moduleName);

			$view=self::modelBindView($moduleName,$router,$current_action);
			if ($current_action->isRedirected) {
				$isValidRequet=true;
				//break;
			}else{				
				$output=self::output($moduleName,$router,$current_action);
				if (self::$isOutputStatic){
					return $output;
				}else{
					echo $output;
				}
				$isValidRequet=true;
			//break;
			}
		}else {
		  include_once(Gc::$nav_root_path.Router::URL_DEFAULT_CONTROLLER.Config_F::SUFFIX_FILE_PHP);
		  return;
		}
		//}
		if (!$isValidRequet) {
			LogMe::record(Wl::ERROR_INFO_CONTROLLER_UNKNOWN);
		}
		if (Gc::$dev_profile_on) Profiler::unmark(Wl::LOG_INFO_PROFILE_WEBURL);
		if (Gc::$dev_profile_on) {
			Profiler::unmark(Wl::LOG_INFO_PROFILE_RUN);
			Profiler::show(true);
		}
	}

	/**
	 * 将控制器与视图进行绑定
	 */
	public static function modelBindView($moduleName,$router,&$current_action) 
	{
		UnitTest::setUp();
		ob_start();
		$controller = $router->getController();
		$action = $router->getAction();
		$extras = $router->getExtras();
		$data=$router->getData();
		if (method_exists($current_action,"setData")){
			$current_action->setData($data);
		}else{
			die ("请检查控制器定义类是否继承了Action!");
		}
		$current_action->setExtras($extras);
		/**
		 * 将控制器与视图进行绑定
		 */
		$templateFile=$controller.DIRECTORY_SEPARATOR.$action;
		$view = Loader::load(Loader::CLASS_VIEW,$moduleName,$templateFile);
		
		if (self::$isOutputStatic){
			if (($view!=null)&&($view->viewObject!=null)){
				$view->viewObject->css_ready="";
				$view->viewObject->js_ready="";
			}
			UtilAjax::$JsLoaded=array();
			UtilCss::$CssLoaded=array();
		}
		$current_action->setView($view);
		ob_end_clean();
		if (method_exists($current_action,$action)){
			if (method_exists($current_action,"beforeAction")){
				$current_action->beforeAction();	
			}
			$current_action->$action();
			if (method_exists($current_action,"afterAction")){
				$current_action->afterAction();   
			}
		}else{   
		  include_once(Gc::$nav_root_path.Router::URL_DEFAULT_CONTROLLER.Config_F::SUFFIX_FILE_PHP);
		  return;
		}
		UnitTest::tearDown();
		return $view;
	}

	/**
	 * 管理视图：输出结果
	 * @var View $view 视图
	 */
	public static function output($moduleName,$router,$current_action) 
	{
		ob_start();
		$view=$current_action->getView();
		$controller = $router->getController();
		$action = $router->getAction();
		$templateFile=$controller.DIRECTORY_SEPARATOR.$action;//模板文件路径名称
		$controller_path=$router->getController_path();
		if (!empty($controller_path)){
			if (endWith($controller_path,DIRECTORY_SEPARATOR)){
				$templateFile=$controller_path.$templateFile;   
			}else{
				$templateFile=$controller_path.DIRECTORY_SEPARATOR.$templateFile;
			}
		}
		if (!file_exists(Gc::$nav_root_path.$view->template_dir().$templateFile.$view->template_suffix_name())) {
			throw new Exception(" view/{$controller}".Wl::ERROR_INFO_VIEW_UNKNOWN." '".$action.$view->template_suffix_name()."'");
		}
		$view->output($templateFile,$view->templateMode(),$current_action);
		$output = ob_get_clean();
		return $output;
	}
	
	/**
	 +----------------------------------------<br/>
	 * URL重定向<br/>
	 +----------------------------------------
	 * @param string $url 跳转的URL路径
	 * @param <type> $time 定时
	 * @param <type> $msg 显示信息
	 */
	public static function redirect($url,$time=0,$msg='') 
	{
		//多行URL地址支持
		$url = str_replace(array("\n", "\r"), '', $url);
		if(empty($msg))
			$msg =  Wl::INFO_REDIRECT_PART1.$time.Wl::INFO_REDIRECT_PART2.$url;
		if (!headers_sent()) {
			// redirect
			if(0===$time) {
				header("Location: ".$url);
			}else {
				header("refresh:{$time};url={$url}");
				echo($msg);
			}
			exit();
		}else {
			$str    = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
			if($time!=0)
				$str   .=   $msg;
			exit($str);
		}
	}
}
?>
