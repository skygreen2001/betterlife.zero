<?php
/**
 * 在线编辑器的类型
 */
class EnumOnlineEditorType extends Enum
{
	/**
	 * @link http://ckeditor.com/
	 */
	const CKEDITOR=1;
	/**
	 * @link http://www.kindsoft.net/
	 */
	const KINDEDITOR=2;
	/**
	 * @link http://xheditor.com/
	 */
	const XHEDITOR=3;
	/**
	 * @link http://ueditor.baidu.com/
	 */
	const UEDITOR=4;
}

/**
 +----------------------------------------------<br/>
 * 所有控制器的父类<br/>
 * class_alias("Action","Controller");<br/>
 +----------------------------------------------
 * @category betterlife
 * @package core.model
 * @author skygreen
 */
class ActionBasic extends Object
{
	/**
	 * 规范要求：所有控制器要求的前缀
	 */
	const ROUTINE_CLASS_PREFIX="Action_";
	/**
	 * SEO：keywords
	 */
	public $keywords;
	/**
	 * SEO：description
	 */
	public $description;
	/**
	 * 在线编辑器,参考:EnumOnlineEditorType
	 * 1.CKEDITOR
	 * 2.KINDEDITOR
	 * 3.XHEDITOR
	 * 4.UEDITOR
	 * @var mixed
	 */
	public $online_editor=EnumOnlineEditorType::UEDITOR;//CKEDITOR
	/**
	 * 访问应用名
	 * @var string
	 */
	protected $modulename;
	/**
	 * 单例实体数据模型
	 * @var Model
	 */
	protected $model;
	/**
	 * 显示器
	 * @var object
	 */
	public $view;
	/**
	 * 来自用户请求里的数据
	 * @var array
	 */
	protected $data;
	/**
	 * 其他系统提供的信息
	 * @var array
	 */
	protected $extras;
	/**
	 * 是否在请求内部重导向->跳转
	 * @var bool
	 */
	public $isRedirected=false;

	/**
	 * 构造器
	 * @param $moduleName 访问应用名
	 */
	public function __construct($moduleName)
	{
		$this->modulename=$moduleName;
		$this->model=Loader::load(Loader::CLASS_MODEL);
		$this->model->setAction($this);
	}

	/**
	 * 设置显示器
	 * @param View $view
	 */
	public function setView($view)
	{
		$this->view=$view;
	}

	/**
	 * 获取显示器
	 * @return View 显示器
	 */
	public function getView()
	{
		return $this->view;
	}

	/**
	 * 设置用户请求数据
	 * @param array $data 用户请求数据
	 */
	public function setData($data)
	{
		$this->data=$data;
	}

	/**
	 * 获取用户请求数据
	 * @return array 用户请求数据
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * 加载通用的Css<br/>
	 * 默认:当前模板目录下:resources/css/index.css<br/>
	 */
	public function loadCss($defaultCssFile="resources/css/index.css")
	{
        if (contain($defaultCssFile,"misc/js/ajax/")){
		    $defaultCssFile=Gc::$url_base.$defaultCssFile;
        }else{
            $defaultCssFile=$this->view->template_url.$defaultCssFile;
        }
		$viewObject=$this->view->viewObject;
		if(empty($viewObject))
		{
			$this->view->viewObject=new ViewObject();
		}
		if ($this->view->viewObject)
		{
			UtilCss::loadCssReady($this->view->viewObject,$defaultCssFile,true);
		}else{
			UtilCss::loadCss($defaultCssFile,true);
		}
	}

	/**
	 * 加载通用的Javascript库<br/>
	 * 默认:当前模板目录下:js/index.js<br/>
	 * @param string $defaultJsFile 默认需加载JS文件
	 */
	public function loadJs($defaultJsFile="js/index.js")
	{
        if (startWith($defaultJsFile,"misc/js/ajax/")){
		    $defaultJsFile=Gc::$url_base.$defaultJsFile;
        }else{
            $defaultJsFile=$this->view->template_url.$defaultJsFile;
        }
		$viewObject=$this->view->viewObject;
		if(empty($viewObject))
		{
			$this->view->viewObject=new ViewObject();
		}
		if ($this->view->viewObject){
			UtilJavascript::loadJsReady($this->view->viewObject,$defaultJsFile,true);
		}else{
			UtilJavascript::loadJs($defaultJsFile,true);
		}
	}

	/**
	 * 查看用户请求数据里是否存在某参数
	 * @param $param 参数
	 */
	public function isDataHave($param)
	{
		 if (array_key_exists($param,$this->data)){
		   return true;
		 }
		 return false;
	}
	/**
	 * 设置其他系统提供的信息
	 * @param array $extras 其他信息
	 */
	public function setExtras($extras)
	{
		$this->extras=$extras;
	}

   /**
	* 内部转向到指定网页地址
	* @param mixed $url URL完整路径包括querystring
	* @link http://localhost/betterlife/index.php?g=betterlife&m=blog&a=display&pageNo=8
	*/
   public function redirect_url($url)
   {
	   if (contain($url,"http://")){
		   header("Location:".$url);
	   }else{
		   header("Location:http://".$url);
	   }
   }

	/**
	 * 内部转向到另一网页地址
	 *
	 * @param mixed $action
	 * @param mixed $method
	 * @param array|string $querystringparam
	 * 示例：
	 *     index.php?g=betterlife&m=blog&a=write&pageNo=8&userId=5
	 *     $action：blog
	 *     $method：write
	 *     $querystring：pageNo=8&userId=5
	 *                   array('pageNo'=>8,'userId'=>5)
	 */
	public function redirect($action,$method,$querystring="")
	{
		$urlMode=Gc::$url_model;
		$extraUrlInfo="";
		$CONNECTOR=Router::URL_SLASH;
		$CONNECTOR_VAR=Router::URL_SLASH;
		if($urlMode == Router::URL_COMMON) {
			$CONNECTOR=Router::URL_EQUAL;
			$CONNECTOR_VAR=Router::URL_CONNECTOR;
		}
		if (!empty($this->extras)) {
			foreach ($this->extras as $key=>$value) {
				$extraUrlInfo.=$key.$CONNECTOR.$value.$CONNECTOR_VAR;
			}
		}
		if (!empty($querystring)){
			if (is_array($querystring)||is_a($querystring,"DataObjectArray")){
				$querystring_tmp="";
				foreach ($querystring as $key=>$value){
					if ($key==Router::VAR_DISPATCH){
						 $querystring_tmp.=Router::URL_CONNECTOR.$key."=".$this->modulename.".".$action.".".$method;
					}else{
						$querystring_tmp.=Router::URL_CONNECTOR.$key."=".$value;
					}
				}
				$querystring=$querystring_tmp;
			}
			$querystring= Router::URL_CONNECTOR.$querystring;
		}
		$Header_Location="Location:";
		$moreinfo=$extraUrlInfo.$querystring;
		if (empty($moreinfo)){
			$CONNECTOR_LAST="";
		}else{
			if (($urlMode == Router::URL_REWRITE)||($urlMode == Router::URL_PATHINFO)){
				$CONNECTOR_LAST=$CONNECTOR;
			}else{
				$CONNECTOR_LAST=$CONNECTOR_VAR;
			}
		}
		if($urlMode == Router::URL_REWRITE ) {
			 $querystring=str_replace(Router::URL_CONNECTOR,Router::URL_SLASH,$querystring);
			 $querystring=str_replace(Router::URL_EQUAL,Router::URL_SLASH,$querystring);
			 if (Router::URL_PATHINFO_MODEL==Router::URL_PATHINFO_NORMAL) {
				header($Header_Location.Gc::$url_base.Router::VAR_GROUP.$CONNECTOR.$this->modulename.$CONNECTOR.Router::VAR_MODULE.$CONNECTOR.$action.$CONNECTOR.
					Router::VAR_ACTION.$CONNECTOR.$method.$CONNECTOR_LAST.$extraUrlInfo.$querystring); //$this->modulename.$CONNECTOR.
			 }else{
				header($Header_Location.Gc::$url_base.$this->modulename.$CONNECTOR.$action.$CONNECTOR.
					$method.$CONNECTOR_LAST.$extraUrlInfo.$querystring);
			 }
		}elseif ($urlMode == Router::URL_PATHINFO) {
			$querystring=str_replace(Router::URL_CONNECTOR,Router::URL_SLASH,$querystring);
			$querystring=str_replace(Router::URL_EQUAL,Router::URL_SLASH,$querystring);
			if (Router::URL_PATHINFO_MODEL==Router::URL_PATHINFO_NORMAL) {
				header($Header_Location.Gc::$url_base.Router::URL_INDEX.$CONNECTOR.Router::VAR_GROUP.$CONNECTOR.$this->modulename.$CONNECTOR.Router::VAR_MODULE.$CONNECTOR.$action.$CONNECTOR.
						Router::VAR_ACTION.$CONNECTOR.$method.$CONNECTOR_LAST.$extraUrlInfo.$querystring);
			}else {
				header($Header_Location.Gc::$url_base.Router::URL_INDEX.$CONNECTOR.$this->modulename.$CONNECTOR.$action.$CONNECTOR.
						$method.$CONNECTOR_LAST.$extraUrlInfo.$querystring);
			}
		}elseif ($urlMode == Router::URL_COMMON) {
			if(!empty($_GET[Router::VAR_DISPATCH])){
				header($Header_Location.Gc::$url_base.Router::URL_INDEX.Router::URL_QUESTION.Router::VAR_DISPATCH.$CONNECTOR.$this->modulename.Router::VAR_DISPATCH_DEPR.$action.Router::VAR_DISPATCH_DEPR.
						$method.$CONNECTOR_LAST.$extraUrlInfo.$querystring);
			}else{
				header($Header_Location.Gc::$url_base.Router::URL_INDEX.Router::URL_QUESTION.Router::VAR_GROUP.$CONNECTOR.$this->modulename.$CONNECTOR_VAR.Router::VAR_MODULE.$CONNECTOR.$action.$CONNECTOR_VAR.
						Router::VAR_ACTION.$CONNECTOR.$method.$CONNECTOR_LAST.$extraUrlInfo.$querystring);
			}
		}elseif ($urlMode == Router::URL_COMPAT) {
			header($Header_Location.Gc::$url_base.Router::URL_INDEX.Router::URL_QUESTION.Router::VAR_PATHINFO.Router::URL_EQUAL.$CONNECTOR_VAR.$this->modulename.$CONNECTOR_VAR.$action.$CONNECTOR_VAR.
					$method.$CONNECTOR_LAST.$extraUrlInfo.$querystring);
		}
		$this->isRedirected=true;
	}

	/**
	 * 加载在线编辑器
	 * @param array|string $textarea_ids Input为Textarea的名称name[一个页面可以有多个Textarea]
	 */
	public function load_onlineditor($textarea_ids="content")
	{
		switch ($this->online_editor) {
			case EnumOnlineEditorType::UEDITOR:
				$viewObject=$this->view->viewObject;
				if(empty($viewObject))
				{
					$this->view->viewObject=new ViewObject();
				}
				UtilJavascript::loadJsReady($this->view->viewObject, "misc/js/onlineditor/ueditor/ueditor.config.js");
				if (UtilAjax::$IsDebug){
					UtilJavascript::loadJsReady($this->view->viewObject, "misc/js/onlineditor/ueditor/ueditor.all.js");
					UtilJavascript::loadJsReady($this->view->viewObject, "misc/js/onlineditor/ueditor/ueditor.parse.js");
				}else{
					UtilJavascript::loadJsReady($this->view->viewObject, "misc/js/onlineditor/ueditor/ueditor.all.min.js");
					UtilJavascript::loadJsReady($this->view->viewObject, "misc/js/onlineditor/ueditor/ueditor.parse.min.js");
				}
				UtilJavascript::loadJsReady($this->view->viewObject, "misc/js/onlineditor/ueditor/lang/zh-cn/zh-cn.js");

				if (is_array($textarea_ids)&&(count($textarea_ids)>0)){
					for($i=0;$i<count($textarea_ids);$i++){
						UtilUeditor::loadJsFunction($textarea_ids[$i],$this->view->viewObject,null);
					}
				}else{
					UtilUeditor::loadJsFunction($textarea_ids,$this->view->viewObject,null);
				}
				$this->view->online_editor="UEditor";
				break;
			case EnumOnlineEditorType::CKEDITOR:
				if (is_array($textarea_ids)&&(count($textarea_ids)>0)){
					$this->view->editorHtml=UtilCKEeditor::loadReplace($textarea_ids[0]);
					for($i=1;$i<count($textarea_ids);$i++){
						$this->view->editorHtml.=UtilCKEeditor::loadReplace($textarea_ids[$i],false);
					}
				}else{
					$this->view->editorHtml=UtilCKEeditor::loadReplace($textarea_ids);
				}
				$this->view->online_editor="CKEditor";
				break;
			case EnumOnlineEditorType::KINDEDITOR:
				$viewObject=$this->view->viewObject;
				if(empty($viewObject))
				{
					$this->view->viewObject=new ViewObject();
				}
				$this->view->keConfig=UtilKindEditor::loadJsFunction($textarea_ids,$this->view->viewObject);
				$this->view->online_editor="KindEditor";
				break;
			case EnumOnlineEditorType::XHEDITOR:
				$viewObject=$this->view->viewObject;
				if(empty($viewObject))
				{
					$this->view->viewObject=new ViewObject();
				}
				UtilAjaxJquery::load("1.7.1",$this->view->viewObject);
				UtilXheditor::loadcss($this->view->viewObject);
				if (UtilAjax::$IsDebug){
					UtilJavascript::loadJsReady($this->view->viewObject, "misc/js/onlineditor/xheditor/xheditor-1.1.13-zh-cn.js");
				}else{
					UtilJavascript::loadJsReady($this->view->viewObject, "misc/js/onlineditor/xheditor/xheditor-1.1.13-zh-cn.min.js");
				}
				UtilXheditor::loadJsPlugin($this->view->viewObject);
				if (is_array($textarea_ids)&&(count($textarea_ids)>0)){
					for($i=0;$i<count($textarea_ids);$i++){
						UtilXheditor::loadJsFunction($textarea_ids[$i],$this->view->viewObject,null,"width:'100%',height:'350px',");
					}
				}else{
					UtilXheditor::loadJsFunction($textarea_ids,$this->view->viewObject,null,"width:'100%',height:'350px',");
				}
				$this->view->online_editor="xhEditor";
				break;
		}
	}

	/**
	 * 在Action所有的方法执行之前可以执行的方法
	 */
	public function beforeAction()
	{
		$this->keywords=Gc::$site_name;
		$this->description=Gc::$site_name;
		/**
		 * 设定网站语言，最终需由用户设置
		 */
		class_alias(ucfirst(Gc::$language),Config_C::WORLD_LANGUAGE);
	}

	/**
	 * 在Action所有的方法执行之后可以执行的方法
	 */
	public function afterAction()
	{
		$this->view->set("keywords",$this->keywords);
		$this->view->set("description",$this->description);
	}
}

?>
