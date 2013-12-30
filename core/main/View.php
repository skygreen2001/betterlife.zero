<?php
/**
 +--------------------------------------------------<br/>
 * View Egine<br/>
 * 用于Template Engine<br/>
 * 方便开发者在controller里通过$this->view->set(varname, value)控制<br/>
 * 以便在显示层页面里任意访问使用变量varname<br/>
 +--------------------------------------------------<br/>
 * @category betterlife
 * @package core.main
 * @author skygreen
 */
class View {
	/**
	 * 显示层文件所在目录名称
	 */
	const VIEW_DIR_VIEW="view";
	/**
	 * 模板模式
	 */
	const TEMPLATE_MODE_NONE=0;
	const TEMPLATE_MODE_SMARTY=1;
	const TEMPLATE_MODE_SMARTTEMPLATE=2;
	const TEMPLATE_MODE_EASETEMPLATE=3;
	const TEMPLATE_MODE_TEMPLATELITE=4;
	const TEMPLATE_MODE_FLEXY=5;

	/**
	 * EaseTemplate 是否开启Memcache
	 */
	const TEMPLATE_EASETEMPLATE_MEMCACHE_ON=false;

	private $vars=array();
	/**
	 * 显示页面上使用的变量存储对象
	 * 目前需模版是
	 *     Smarty:TEMPLATE_MODE_SMARTY
	 *     Flexy :TEMPLATE_MODE_FLEXY方能支持
	 */
	private $viewObject;
	/**
	 * 访问应用名
	 * @var string
	 */
	private $moduleName;
	private $template;//模板
	private $templateMode;//模板模式
	/**
	 * @var string 模板规范要求所在的目录
	 */
	private $template_dir;
	/**
	 * @var string 模板文件规范要求的文件后缀名称
	 */
	private $template_suffix_name;

	/**
	 * @param array 显示层需要使用的全局变量
	 */
	protected static $view_global=array();
	private function init_view_global(){
		self::$view_global=array(
			"url_base"=> Gc::$url_base,
			"encoding"=> Gc::$encoding,
			"site_name"=> Gc::$site_name,
			"template_url"=>$this->template_url_dir(),
			"upload_url"=>Gc::$upload_url,
			"templateDir"=> Gc::$nav_root_path.$this->getTemplate_View_Dir($this->moduleName),
		);
	}
	/**
	* @param mixed $moduleName 访问应用名
	* @param mixed $templatefile 模板文件
	* @return View
	*/
	public function __construct($moduleName,$templatefile=null) {
		$this->moduleName=$moduleName;
		self::init_view_global();
		if (isset(Gc::$template_mode_every)&&array_key_exists($this->moduleName,Gc::$template_mode_every)){
			$this->initTemplate(Gc::$template_mode_every[$this->moduleName],$templatefile);
		}else{
			$this->initTemplate(Gc::$template_mode,$templatefile);
		}
		if (!empty(self::$view_global)) {
			foreach (self::$view_global as $key=>$value) {
				$this->template_set($key, $value);
				if (is_array($this->vars)) {
					$this->vars[$key]=$value;
				} else {
					$this->vars->$key=$value;
				}
			}
		}
	}

	public function set($key, $value,$template_mode=null) {
		$this->template_set($key, $value,$template_mode);
	}

	public function get($property) {
		if (is_array($this->vars)) {
			return $this->vars[$key];
		} else {
			return $this->vars->$key;
		}

	}

	/***********************************魔术方法**************************************************/
	/**
	 * 说明：若每个具体的实现类希望不想实现set,get方法；
	 *      则将该方法复制到每个具体继承他的对象类内。
	 * 可设定对象未定义的成员变量[但不建议这样做]
	 * 可无需定义get方法和set方法
	 * 类定义变量访问权限设定需要是pulbic
	 * @param <type> $property
	 */
	public function __call($method, $arguments) {
		if (UtilString::contain($method,"set")) {
			$property=substr($method,strlen("set"),strlen($method));
			$property{0}=strtolower($property{0});
			if (property_exists($this,$property)) {
				$this->$property=$arguments[0];
			} else {
				$this->set($property,$arguments[0]);
			}
		}
		else if (UtilString::contain($method,"get")) {
			$property=substr($method,strlen("get"),strlen($method));
			$property{0}=strtolower($property{0});
			if (is_array($this->vars)) {
				return $this->vars[$property];
			} else {
				return $this->vars->$property;
			}
		}
	}

	public function __set($property, $value) {
		if (property_exists($this,$property)) {
			if ((!empty($property))&&($property=='viewObject')&&
				!empty($this->viewObject->js_ready)&&array_key_exists('js_ready',$value)){
			   $value->js_ready= $this->viewObject->js_ready.$value->js_ready;
			}
			if ((!empty($property))&&($property=='viewObject')&&
				!empty($this->viewObject->css_ready)&&array_key_exists('css_ready',$value)){
			   $value->css_ready= $this->viewObject->css_ready.$value->css_ready;
			}
			$this->$property=$value;
		} else {
			$this->set($property,$value);
		}
	}

	public function __get($property) {
		if (property_exists($this,$property)) {
			return $this->$property;
		} else {
			if (is_array($this->vars)) {
				return $this->vars[$property];
			} else {
				return $this->vars->$property;
			}
		}
	}

	public function getVars() {
		return $this->vars;
	}

	/**
	 * 获取模板
	 * @return <type> huoqu
	 */
	public function template() {
		return $this->template;
	}

	/**
	 * 获取当前模板模式
	 */
	public function templateMode() {
		return $this->templateMode;
	}

	/**
	 * @return string 模板文件所在的目录
	 */
	public function template_dir() {
		return $this->template_dir;
	}
	/**
	 * @return string 模板文件所在的目录
	 */
	public function template_url_dir(){
		return @Gc::$url_base.str_replace("\\","/",$this->getTemplate_View_Dir());
	}

	/**
	 * @return string 模板文件的文件后缀名称
	 */
	public function template_suffix_name() {
		return $this->template_suffix_name;
	}

	/**
	 * 当在同一种网站里使用多个模板的时候
	 * 通过本函数进行指定
	 */
	public function setTemplate($template_mode,$moduleName,$templatefile=null) {
		$this->moduleName=$moduleName;
		if (empty($template_mode)) {
			$template_mode=Gc::$template_mode;
		}
		$this->initTemplate($template_mode,$templatefile=null);
	}

	/**
	* 获取模板文件完整的路径
	*
	*/
	private function getTemplate_View_Dir(){
	   $result="";
	   if (strlen(Gc::$module_root)>0) {
		  $result.=Gc::$module_root.DIRECTORY_SEPARATOR;
	   }
	   $result.= $this->moduleName.DIRECTORY_SEPARATOR.self::VIEW_DIR_VIEW.DIRECTORY_SEPARATOR;
	   if (isset(Gc::$self_theme_dir_every)&&array_key_exists($this->moduleName,Gc::$self_theme_dir_every)){
		   $result.=Gc::$self_theme_dir_every[$this->moduleName].DIRECTORY_SEPARATOR;
	   }else{
		   $result.=Gc::$self_theme_dir.DIRECTORY_SEPARATOR;
	   }
	   return $result;
	}

	/**
	 * 初始化模板文件
	 * @var string $template_mode 模板模式
	 */
	private function initTemplate($template_mode,$templatefile=null) {
		if (empty($template_mode)) {
			$template_mode=Gc::$template_mode;
		}
		$this->template_dir=$this->getTemplate_View_Dir($this->moduleName).Config_F::VIEW_CORE.DIRECTORY_SEPARATOR;
		$template_tmp_dir= $this->getTemplate_View_Dir($this->moduleName)."tmp".DIRECTORY_SEPARATOR;
		$this->template_suffix_name=Gc::$template_file_suffix;

		switch ($template_mode) {
			case self::TEMPLATE_MODE_SMARTY:
				$this->templateMode=self::TEMPLATE_MODE_SMARTY;
				$this->template = new Smarty;
				$this->template->template_dir =  Gc::$nav_root_path.$this->template_dir;
				$this->template->compile_dir =  Gc::$nav_root_path.$template_tmp_dir."templates_c".DIRECTORY_SEPARATOR;
				$this->template->config_dir =  $template_tmp_dir."configs".DIRECTORY_SEPARATOR;
				$this->template->cache_dir =  $template_tmp_dir."cache".DIRECTORY_SEPARATOR;
				$this->template->compile_check = true;
				$this->template->allow_php_templates= true;
				$this->template->allow_php_tag=true;
				$this->template->debugging = Gc::$dev_debug_on;
				$this->template->force_compile = false;
				$this->template->caching = Gc::$is_online_optimize;
				$this->template->cache_lifetime = 86400;//缓存一周
				UtilFileSystem::createDir($this->template->compile_dir);
				$is_win=is_server_windows();
				if (!$is_win){
					$isRoot= fileperms($this->template->compile_dir);
					$isRoot= substr(sprintf('%o',$isRoot),-4);
					if (!is_dir($this->template->compile_dir)||($isRoot=='0755')){
						die("因为安全原因，需要手动在操作系统中创建目录:".$this->template->compile_dir."<br/>".
							"Linux command need:<br/>".str_repeat("&nbsp;",40).
							"sudo mkdir -p ".$this->template->compile_dir."<br/>".str_repeat("&nbsp;",40).
							"sudo chmod 0777 ".$this->template->compile_dir);
					}
				}
				break;
			case self::TEMPLATE_MODE_SMARTTEMPLATE:
				$this->templateMode=self::TEMPLATE_MODE_SMARTTEMPLATE;
				$templateFilePath=basename($templatefile).$this->template_suffix_name;
				$this->template = new QuickSkin($templateFilePath);
				$this->template->template_dir= $this->template_dir;
				$this->template->temp_dir= $template_tmp_dir."temp".DIRECTORY_SEPARATOR;
				$this->template->cache_dir= $template_tmp_dir."cache".DIRECTORY_SEPARATOR;
				break;
			case self::TEMPLATE_MODE_EASETEMPLATE:
				$this->templateMode=self::TEMPLATE_MODE_EASETEMPLATE;
				$lan_dir=Initializer::$NAV_CORE_PATH.Config_F::CORE_LANG.DIRECTORY_SEPARATOR;
				$tpl_set = array(
						'ID'        =>'1',            //缓存ID
						'TplType'    =>str_replace(".","",Gc::$template_file_suffix),//模板格式
						'CacheDir'    => $template_tmp_dir.'cache'.DIRECTORY_SEPARATOR,        //缓存目录<br />
						'TemplateDir'    => $this->template_dir,//模板存放目录<br />
						'AutoImage'    =>'on',//自动解析图片目录开关 on表示开放 off表示关闭<br />
						'LangDir'    =>$lan_dir,//语言文件存放的目录<br />
						'Language'    =>Config_C::WORLD_LANGUAGE,//语言的默认文件<br />
						'Copyright'    =>'off',//版权保护<br />
				);
				if (self::TEMPLATE_EASETEMPLATE_MEMCACHE_ON) {

					$tpl_set['MemCache']="http://".Cache_Memcache::$host.":".Cache_Memcache::$port;
				}
				$this->template = new template($tpl_set);
				break;
			case self::TEMPLATE_MODE_TEMPLATELITE:
				$this->templateMode=self::TEMPLATE_MODE_TEMPLATELITE;
				$this->template= new Template_Lite;
				$this->template->template_dir = $this->template_dir;
				$this->template->compile_dir =  $template_tmp_dir."compiled".DIRECTORY_SEPARATOR;
				break;
			case self::TEMPLATE_MODE_FLEXY:
				$this->templateMode=self::TEMPLATE_MODE_FLEXY;
				$dir_pos=strrpos($templatefile, DIRECTORY_SEPARATOR);
				$sub_dir="";
				if ($dir_pos>0) {
					$sub_dir= substr($templatefile,0,$dir_pos);
				}
				$this->template= new HTML_Template_Flexy(array(
								'templateDir' => $this->template_dir.$sub_dir,
								'compileDir' => $template_tmp_dir.'compiled'.DIRECTORY_SEPARATOR,
								'debug' => Gc::$dev_debug_on,
								'forceCompile'  =>  true,  // only suggested for debugging
								'fatalError'=> HTML_TEMPLATE_FLEXY_ERROR_DIE,
								'globals'=>true,/*可以在页面模板中使用$_Session,$_Get*/
								'allowPHP'=> true,
								'flexyIgnore'=>true
				));
				$templateFilePath=basename($templatefile).$this->template_suffix_name;
				if (file_exists(Gc::$nav_root_path.$this->template_dir.$templatefile.$this->template_suffix_name)) {
					$this->template->compile($templateFilePath);
				}
				break;
			default:
				$this->templateMode=self::TEMPLATE_MODE_NONE;
				break;
		}
	}

	/**
	 * 设置模板认知的变量
	 */
	public function template_set($key,$value,$template_mode=null) {
		if (empty($template_mode)) {
			if (isset(Gc::$template_mode_every)&&array_key_exists($this->moduleName,Gc::$template_mode_every)){
				$template_mode=Gc::$template_mode_every[$this->moduleName];
			}else{
				$template_mode=Gc::$template_mode;
			}
		}
		switch ($template_mode) {
			case self::TEMPLATE_MODE_NONE:
				break;
			case self::TEMPLATE_MODE_SMARTY:
			case self::TEMPLATE_MODE_SMARTTEMPLATE:
			case self::TEMPLATE_MODE_TEMPLATELITE:
				$this->template->assign($key,$value);
				break;
			case self::TEMPLATE_MODE_EASETEMPLATE:
				$this->template->set_var($key,$value);
				break;
			case self::TEMPLATE_MODE_FLEXY:
				if (!is_object($this->vars)) {
					$this->vars=new stdClass();
				}
				break;
		}
		if (is_array($this->vars)) {
			$this->vars[$key]=$value;
		} else {
			$this->vars->$key=$value;
		}
	}

	/**
	 * 渲染输出
	 * @param string $templatefile 模板文件名
	 */
	public function output($templatefile,$template_mode,$controller=null) {
		if (empty($template_mode)) {
			$template_mode=Gc::$template_mode;
		}
		$templateFilePath=$templatefile.$this->template_suffix_name;
		switch ($template_mode) {
			case self::TEMPLATE_MODE_SMARTY:
				if(!empty($this->viewObject)){
				  $view_array=UtilObject::object_to_array($this->viewObject,$this->vars);
				  foreach($view_array as $key=>$value){
					if (!array_key_exists($key,self::$view_global)){
					  $this->set($key,$value);
					}
				  }
				  $name_viewObject=ViewObject::get_Class();
				  $name_viewObject{0}=strtolower($name_viewObject{0});
				  $this->template->assignByRef($name_viewObject,$this->viewObject);
				}

				$this->template->display($templateFilePath);
				break;
			case self::TEMPLATE_MODE_SMARTTEMPLATE:
				$sub_dir=explode(DIRECTORY_SEPARATOR,$templateFilePath);
				if (!empty($sub_dir)) {
					$this->template->template_dir.=$sub_dir[0];
				}
				$this->template->output();
				break;
			case self::TEMPLATE_MODE_EASETEMPLATE:
				$filename_dir=explode(DIRECTORY_SEPARATOR,$templateFilePath);
				if (!empty($sub_dir)) {
					$filename_dir=$sub_dir[0];
				}
				$this->template->set_file(basename($filename_dir[1],Gc::$template_file_suffix),$filename_dir[0]);
				//打印模板
				$this->template->p();
				break;
			case self::TEMPLATE_MODE_TEMPLATELITE:
				$this->template->display($templateFilePath);
				break;
			case self::TEMPLATE_MODE_FLEXY:
				if(empty($this->viewObject)) {
					$this->template->outputObject($this->vars);
				} else {
					foreach ($this->vars as $key=>$value) {
						$this->viewObject->$key=$value;
					}
					$this->template->outputObject($this->viewObject);
				}
				break;
			default:
				$viewvars = $this->getVars($controller);
				extract($viewvars);
				include_once(Gc::$nav_root_path.$this->template_dir().$templateFilePath);
				break;
		}
	}
}
?>
