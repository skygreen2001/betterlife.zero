<?php
$actionExt =<<<ACTIONEXT
<?php
/**
 +----------------------------------------------<br/>
 * 所有采用Ext JS Javascript框架的控制器的父类<br/>
 +----------------------------------------------
 * @category betterlife
 * @package web.back.admin
 * @author $author
 */
class ActionExt extends ActionBasic
{
	/**
	 * 加载Ext 第三方定义组件定义对象
	 */
	public function loadExtComponent(\$objectFile)
	{
		\$this->loadExtJs("components/\$objectFile");
	}

	/**
	 * 加载Ext 显示层定义对象
	 * @param \$viewFile 显示的文件路径
	 * @param bool \$isGzip 是否使用Gzip进行压缩。
	 */
	public function loadExtJs(\$viewFile,\$isGzip=false)
	{
		if (UtilAjaxExtjs::\$ext_version<4){
			\$module_templateurl_relative="js/ext/";
		}else{
			\$module_templateurl_relative="js/ext4/";
		}
		\$templateurl=\$this->view->template_url;
		if (\$isGzip&&startWith(\$viewFile,'shared')){
			UtilJavascript::loadJsReady(\$this->view->viewObject, \$viewFile,\$isGzip,EnumJsFramework::JS_FW_EXTJS,UtilAjaxExtjs::\$ext_version);
		}else{
			\$path=\$templateurl.\$module_templateurl_relative;
			UtilJavascript::loadJsReady(\$this->view->viewObject, \$path.\$viewFile,\$isGzip);
		}

	}

	/**
	 * 加载Ext 显示层Css文件
	 * @param \$viewCss 显示的Css文件路径
	 * @param bool \$isGzip 是否使用Gzip进行压缩。
	 */
	public function loadExtCss(\$viewCss,\$isGzip=false)
	{
		\$templateurl=\$this->view->template_url;
		\$viewObject=\$this->view->viewObject;
		if(empty(\$viewObject))
		{
			\$this->view->viewObject=new ViewObject();
		}
		if (\$this->view->viewObject)
		{
			if (\$isGzip&&startWith(\$viewCss,'shared')){
				UtilAjax::init();
				UtilCss::loadCssReady(\$this->view->viewObject,\$viewCss,\$isGzip,EnumJsFramework::JS_FW_EXTJS,UtilAjaxExtjs::\$ext_version);
			}else{
				if(startWith(\$viewCss,'shared')){
					UtilCss::loadCssReady(\$this->view->viewObject,\$viewCss,\$isGzip);
				}else{
					UtilCss::loadCssReady(\$this->view->viewObject,\$templateurl."resources/css/".\$viewCss,\$isGzip);
				}
			}
		}else{
			UtilCss::loadCss(\$templateurl."resources/css/".\$viewCss,true);
		}
	}

	/**
	 * 加载 组件的Css
	 * @param \$viewCss 显示的Css文件路径
	 * @param bool \$isGzip 是否使用Gzip进行压缩。
	 */
	public function loadExtComponentCss(\$viewCss,\$isGzip=true)
	{
		 \$this->loadExtCss("shared/css/".\$viewCss, \$isGzip);
	}

	/**
	 * 使用Ext Direct Remote 模式
	 */
	public function ExtDirectMode()
	{
		UtilJavascript::loadJsReady(\$this->view->viewObject, "home/admin/src/services/ajax/extjs/direct/api.php");
	}

	/**
	 *  使用Ext 上传功能
	 */
	public function ExtUpload()
	{
		 \$this->loadExtComponentCss("fileuploadfield.css",true);
		 \$this->loadExtComponent("FileUploadField.js");
	}

	/**
	 * Ext请求返回 Response
	 * @param mixed \$response
	 * @param mixed \$isFormAndIsUpload
	 */
	public static function ExtResponse(\$response,\$isFormAndIsUpload=false)
	{
		if (\$isFormAndIsUpload) {
			echo '<html><body><textarea>';
			echo json_encode(\$response);
			echo '</textarea></body></html>';
		} else {
			echo json_encode(\$response);
		}
		die();
	}

	/**
	 * 在Action所有的方法执行之前可以执行的方法
	 */
	public function beforeAction()
	{
		if (contain(\$this->data["go"],"admin")){
			if((\$this->data["go"]!="admin.index.login")&&!HttpSession::isHave(Gc::\$appName_alias.'admin_id')) {
				if (\$this->data["go"]=="admin.index.index"){
					\$this->redirect("index","login");
				}else{
					UtilJavascript::loadJsContent("window.parent.location='".Gc::\$url_base."index.php?go=admin.index.login");
				}
			}
			if (HttpCookie::get("OnlineEditor")){
				\$this->online_editor=HttpCookie::get("OnlineEditor");
			}
		}
	}

	/**
	 * 在Action所有的方法执行之后可以执行的方法
	 */
	public function afterAction()
	{
	}

	/**
	 * 初始化，加载Css和Javascript库。
	 */
	protected function init()
	{
		//初始化加载Css和Javascript库
		\$this->view->viewObject=new ViewObject();
		UtilCss::loadExt(\$this->view->viewObject,UtilAjaxExtjs::\$ext_version);
		UtilAjaxExtjs::loadUI(\$this->view->viewObject,UtilAjaxExtjs::\$ext_version);

		UtilFileSystem::createDir(Gc::\$attachment_path);
		UtilFileSystem::createDir(Gc::\$upload_path);
		if (!is_dir(Gc::\$attachment_path)){
			die("因为安全原因，需要手动在操作系统中创建目录:".Gc::\$attachment_path."<br/>".
				"Linux command need:<br/>".str_repeat("&nbsp;",40).
				"sudo mkdir -p ".Gc::\$attachment_path."<br/>".str_repeat("&nbsp;",40).
				"sudo chmod -R 0777 ".Gc::\$attachment_path);
		}
		if (!is_dir(Gc::\$upload_path)){
			die("因为安全原因，需要手动在操作系统中创建目录:".Gc::\$upload_path."<br/>".
				"Linux command need:<br/>".str_repeat("&nbsp;",40).
				"sudo mkdir -p ".Gc::\$upload_path."<br/>".str_repeat("&nbsp;",40).
				"sudo chmod -R 0777 ".Gc::\$upload_path);
		}
	}
}
?>

ACTIONEXT;

$action =<<<ACTION
<?php
/**
 +----------------------------------------------<br/>
 * 所有控制器的父类<br/>
 +----------------------------------------------
 * @category betterlife
 * @package  web.front
 * @author $author
 */
class Action extends ActionBasic
{
	/**
	 * 在Action所有的方法执行之前可以执行的方法
	 */
	public function beforeAction()
	{
		parent::beforeAction();
		if (contain(\$this->data["go"],Gc::\$appName)){
			if((\$this->data["go"]!=Gc::\$appName.".auth.register")&&(\$this->data["go"]!=Gc::\$appName.".auth.login")&&!HttpSession::isHave('user_id')) {
				\$this->redirect("auth","login");
			}
		}
	}

	/**
	 * 在Action所有的方法执行之后可以执行的方法
	 */
	public function afterAction()
	{
		parent::afterAction();
	}
}

?>
ACTION;

$actionModel =<<<ACTIONMODEL
<?php
/**
 +----------------------------------------------<br/>
 * 所有Model应用控制器的父类<br/>
 +----------------------------------------------
 * @category betterlife
 * @package web.model
 * @author $author
 */
class ActionModel extends ActionBasic
{
	/**
	 * 在Action所有的方法执行之前可以执行的方法
	 */
	public function beforeAction()
	{
		parent::beforeAction();
	}

	/**
	 * 在Action所有的方法执行之后可以执行的方法
	 */
	public function afterAction()
	{
		parent::afterAction();
	}

	/**
	 * 上传图片文件
	 * @param array \$files 上传的文件对象
	 * @param array \$uploadFlag 上传标识,上传文件的input组件的名称
	 * @param array \$upload_dir 上传文件存储的所在目录[最后一级目录，一般对应图片列名称]
	 * @param array \$categoryId 上传文件所在的目录标识，一般为类实例名称
	 * @return array 是否创建成功。
	 */
	public function uploadImg(\$files,\$uploadFlag,\$upload_dir,\$categoryId="default")
	{
		\$diffpart=date("YmdHis");
		\$result="";
		if (!empty(\$files[\$uploadFlag])&&!empty(\$files[\$uploadFlag]["name"])){
			\$tmptail = end(explode('.', \$files[\$uploadFlag]["name"]));
			\$uploadPath =GC::\$upload_path."images".DIRECTORY_SEPARATOR.\$categoryId.DIRECTORY_SEPARATOR.\$upload_dir.DIRECTORY_SEPARATOR.\$diffpart.".".\$tmptail;
			\$result	 =UtilFileSystem::uploadFile(\$files,\$uploadPath,\$uploadFlag);
			if (\$result&&(\$result['success']==true)){
				\$result['file_name']="\$categoryId/\$upload_dir/\$diffpart.\$tmptail";
			}else{
				return \$result;
			}
		}
		return \$result;
	}
}

?>
ACTIONMODEL;


?>