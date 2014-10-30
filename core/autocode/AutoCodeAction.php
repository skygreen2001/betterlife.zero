<?php
/**
 +---------------------------------<br/>
 * 工具类:自动生成代码-控制器<br/>
 +---------------------------------<br/>
 * @category betterlife
 * @package core.autocode
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCodeAction extends AutoCode
{
	/**
	 * 控制器生成定义的方式<br/>
	 * 0.前端Action，继承基本Action。<br/>
	 * 1.生成标准的增删改查模板Action，继承基本Action。<br/>
	 * 2.后端Action，继承ActionExt。<br/>
	 */
	public static $type;
	/**
	 *Action文件所在的路径
	 */
	public static $action_dir="action";
	/**
	 * Action完整的保存路径
	 */
	public static $action_dir_full;
	/**
	 * 表示层Js文件所在的目录
	 */
	public static $view_js_package;
	/**
	 * 需打印输出的文本
	 * @var string
	 */
	public static $echo_result="";
	/**
	* 需打印输出
	* @var string
	*/
	public static $echo_upload="";
	/**
	 * 前端Action所在的namespace
	 */
	private static $package_front="web.front.action";
	/**
	 * 后端Action所在的namespace
	 */
	private static $package_back="web.back.admin";
	/**
	 * 模板Action所在的namespace
	 */
	private static $package_model="web.model.action";

	/**
	 * 自动生成代码-控制器
	 * @param array|string $table_names
	 * 示例如下：
	 *  1.array:array('bb_user_admin','bb_core_blog')
	 *  2.字符串:'bb_user_admin,bb_core_blog'
	 */
	public static function AutoCode($table_names="")
	{
		switch (self::$type) {
			case 0:
				self::$app_dir=Gc::$appName;
				break;
			case 1:
				self::$app_dir="model";
			 	break;
			case 2:
				self::$app_dir="admin";
				break;
		}
		self::$action_dir_full=self::$save_dir.self::$app_dir.DS.self::$action_dir.DS;
		$view_dir_full=self::$save_dir.self::$app_dir.DS.Config_F::VIEW_VIEW.DS.Gc::$self_theme_dir.DS;
		self::$view_js_package=$view_dir_full."js".DS."ext".DS;

		if (!UtilString::is_utf8(self::$action_dir_full)){
			self::$action_dir_full=UtilString::gbk2utf8(self::$action_dir_full);
		}
		self::init();
		if (self::$isOutputCss) self::$showReport.= UtilCss::form_css()."\r\n";
		self::$echo_result="";
		self::$echo_upload="";

		if(self::$type==0) {
			self::$showReport.=AutoCodeFoldHelper::foldbeforeaction0();
			$link_action_dir_href="file:///".str_replace("\\", "/", self::$action_dir_full);
			self::$showReport.= "<font color='#AAA'>存储路径:<a target='_blank' href='".$link_action_dir_href."'>".self::$action_dir_full."</a></font><br/><br/>";
		}else if(self::$type==1) {
			self::$showReport.=AutoCodeFoldHelper::foldbeforeaction1();
			$link_action_dir_href="file:///".str_replace("\\", "/", self::$action_dir_full);
			self::$showReport.= "<font color='#AAA'>存储路径:<a target='_blank' href='".$link_action_dir_href."'>".self::$action_dir_full."</a></font><br/><br/>";
		}

		$fieldInfos=self::fieldInfosByTable_names($table_names);
		foreach ($fieldInfos as $tablename=>$fieldInfo){
			if(self::$type==0) {
				$classname=self::getClassname($tablename);
				if ($classname=="Admin")continue;
			}
			$definePhpFileContent=self::tableToActionDefine($tablename,$fieldInfo);
			if (!empty($definePhpFileContent)){
				if (isset(self::$save_dir)&&!empty(self::$save_dir)&&isset($definePhpFileContent)){
					$classname=self::saveActionDefineToDir($tablename,$definePhpFileContent);
					self::$showReport.= "生成导出完成:$tablename=>$classname!<br/>";
				}else{
					self::$showReport.= $definePhpFileContent."<br/>";
				}
			}
		}

		if(self::$type==0) {
			self::$showReport.= '</div><br/>';
		}else if(self::$type==1) {
			self::$showReport.= '</div>';
		}
		$category_cap=Gc::$appName;
		$category_cap{0}=ucfirst($category_cap{0});
		if (self::$type==2){
			self::$showReport.= "<br/><font color='#FF0000'>[需要在【后台】Action_".$category_cap."里添加没有的代码]</font><br />";
			$category = Gc::$appName;
			$package  = self::$package_back;
			$author   = self::$author;
			include_once("jsmodel".DS."overalljs.php");
			$overalljs_files=array("index.js"=>$jsIndexContent,"layout.js"=>$jsLayoutContent,"navigation.js"=>$jsNavigationContent);
			foreach ($overalljs_files as $filename=>$content) {
				if (!file_exists(self::$view_js_package.$filename)){
					self::saveDefineToDir(self::$view_js_package,$filename,$content);
				}
			}
			$e_index=$loginout."\r\n".
					"	/**\r\n".
					"	 * 控制器:首页\r\n".
					"	 */\r\n".
					"	public function index()\r\n".
					"	{\r\n".
					"		\$this->init();\r\n".
					"		\$this->loadIndexJs();\r\n".
					"		//加载菜单\r\n".
					"		\$this->view->menuGroups=MenuGroup::all();\r\n".
					"	}\r\n\r\n".
					"	/**\r\n".
					"	 * 预加载首页JS定义库。\r\n".
					"	 * @param ViewObject \$viewobject 表示层显示对象\r\n".
					"	 * @param string \$templateurl\r\n".
					"	 */\r\n".
					"	private function loadIndexJs()\r\n".
					"	{\r\n".
					"		\$viewobject=\$this->view->viewObject;\r\n".
					"		\$this->loadExtCss(\"index.css\",true);\r\n".
					"		\$this->loadExtJs(\"index.js\",true);\r\n".
					"		//核心功能:外观展示\r\n".
					"		\$this->loadExtJs(\"layout.js\",true);\r\n".
					"		//左侧菜单组生成显示\r\n".
					"		UtilJavascript::loadJsContentReady(\$viewobject,MenuGroup::viewForExtJs());\r\n".
					"		//核心功能:导航[Tab新建窗口]\r\n".
					"		\$this->loadExtJs(\"navigation.js\",true);\r\n".
					"	}\r\n\r\n";
			$action_names=array("Action_Index"=>$e_index,"Action_".$category_cap=>self::$echo_result);
			foreach ($action_names as $key => $value) {
				$isCreate=true;
				if (($key=="Action_Index")&&(file_exists(self::$action_dir_full.$key.".php")))$isCreate=false;
				if ($isCreate){
					$e_result="<?php\r\n".
							 "/**\r\n".
							 " +---------------------------------------<br/>\r\n".
							 " * 控制器:网站后台管理<br/>\r\n".
							 " +---------------------------------------\r\n".
							 " * @category $category\r\n".
							 " * @package $package\r\n".
							 " * @subpackage action\r\n".
							 " * @author $author\r\n".
							 " */\r\n".
							 "class $key extends ActionExt\r\n".
							 "{\r\n".$value."}\r\n".
							 "?>";
					self::saveDefineToDir(self::$action_dir_full,$key.".php",$e_result);
				}
			}
			$link_action_dir_href="file:///".str_replace("\\", "/", self::$action_dir_full).$key.".php";
			self::$showReport.=  "新生成的Action_{$category_cap}文件路径:<font color='#0000FF'><a target='_blank' href='".$link_action_dir_href."'>".self::$action_dir_full.$key.".php</a></font><br />";

			self::$showReport.= "<br/><font color='#FF0000'>[需要在【后台】Action_Upload里添加没有的代码]</font><br/>";
			$e_result="<?php\r\n".
					 "/**\r\n".
					 " +---------------------------------------<br/>\r\n".
					 " * 控制器:上传文件<br/>\r\n".
					 " +---------------------------------------\r\n".
					 " * @category $category\r\n".
					 " * @package $package\r\n".
					 " * @subpackage action\r\n".
					 " * @author $author\r\n".
					 " */\r\n".
					 "class Action_Upload extends ActionExt\r\n".
					 "{\r\n".self::$echo_upload."}\r\n".
					 "?>";
			self::saveDefineToDir(self::$action_dir_full,"Action_Upload.php",$e_result);

			$link_action_dir_href="file:///".str_replace("\\", "/", self::$action_dir_full);
			self::$showReport.=  "新生成的Action_Upload文件路径:<font color='#0000FF'><a target='_blank' href='".$link_action_dir_href."Action_Upload.php'>".self::$action_dir_full."Action_Upload.php</a></font><br />";
		}
		/**
		 * 生成标准的增删改查模板Action文件需生成首页访问所有生成的链接
		 */
		if ((self::$type==1)||(self::$type==0)){
			self::createModelIndexFile();
		}

		self::createActionParent();
	}

	/**
	* 生成Action的父类。
	* 1.前台生成Action;2.后台生成ActionExt;3.模板生成ActionModel
	*/
	public static function createActionParent()
	{
		$dir_home_app=self::$save_dir.DS.self::$app_dir.DS."action".DS;
		$author=self::$author;
		require("view".DS."jsmodel".DS."actionbasicjs.php");
		switch (self::$type) {
			case 1:
				self::saveDefineToDir($dir_home_app,"ActionModel.php",$actionModel);
				break;
			case 2:
				self::saveDefineToDir($dir_home_app,"ActionExt.php",$actionExt);
				break;
			default:
				self::saveDefineToDir($dir_home_app,"Action.php",$action);
				break;
		}
	}

	/**
	 * 用户输入需求
	 * @param $default_value 默认值
	 */
	public static function UserInput($default_value="")
	{
		$inputArr=array(
			"0"=>"前端Action,继承基本Action",
			"1"=>"生成标准的增删改查模板Action,继承ActionModel",
			"2"=>"后端Action,继承ActionExt"
		);
		parent::UserInput("一键生成控制器Action类定义层",$inputArr,$default_value);
	}

	/**
	 * 将表列定义转换成控制器Php文件定义的内容
	 * @param string $tablename 表名
	 * @param array $fieldInfo 表列信息列表
	 */
	private static function tableToActionDefine($tablename,$fieldInfo)
	{
		$result="<?php\r\n";
		$table_comment=self::tableCommentKey($tablename);
		$category  = Gc::$appName;
		$package   = self::$package_front;
		$classname = self::getClassname($tablename);
		$instancename=self::getInstancename($tablename);
		$appname_alias=strtolower(Gc::$appName_alias);
		$author	= self::$author;
		switch (self::$type) {
			case 2:
				self::$echo_result.=self::createBgActionIndex($tablename,$fieldInfo);
				self::$echo_upload.=self::createBgActionUpload($tablename,$fieldInfo);
				return "";
			case 1:
				$package=self::$package_model;
				$result.="/**\r\n".
						 " +---------------------------------------<br/>\r\n".
						 " * 控制器:$table_comment<br/>\r\n".
						 " +---------------------------------------\r\n".
						 " * @category $category\r\n".
						 " * @package $package\r\n".
						 " * @author $author\r\n".
						 " */\r\n".
						 "class Action_$classname extends ActionModel\r\n".
						 "{\r\n".
						 "	/**\r\n".
						 "	 * {$table_comment}列表\r\n".
						 "	 */\r\n".
						 "	public function lists()\r\n".
						 "	{\r\n".
						 "		if (\$this->isDataHave(UtilPage::\$linkUrl_pageFlag)){\r\n".
						 "			\$nowpage=\$this->data[UtilPage::\$linkUrl_pageFlag];\r\n".
						 "		}else{\r\n".
						 "			\$nowpage=1;\r\n".
						 "		}\r\n".
						 "		\$count={$classname}::count();\r\n".
						 "		\${$appname_alias}_page=UtilPage::init(\$nowpage,\$count);\r\n".
						 "		\$this->view->count{$classname}s=\$count;\r\n".
						 "		\${$instancename}s = {$classname}::queryPage(\${$appname_alias}_page->getStartPoint(),\${$appname_alias}_page->getEndPoint());\r\n".
						 "		\$this->view->set(\"{$instancename}s\",\${$instancename}s);\r\n".
						 "	}\r\n".
						 "	/**\r\n".
						 "	 * 查看{$table_comment}\r\n".
						 "	 */\r\n".
						 "	public function view()\r\n".
						 "	{\r\n".
						 "		\${$instancename}Id=\$this->data[\"id\"];\r\n".
						 "		\${$instancename} = {$classname}::get_by_id(\${$instancename}Id);\r\n".
						 "		\$this->view->set(\"{$instancename}\",\${$instancename});\r\n".
						 "	}\r\n".
						 "	/**\r\n".
						 "	 * 编辑{$table_comment}\r\n".
						 "	 */\r\n".
						 "	public function edit()\r\n".
						 "	{\r\n".
						 "		if (!empty(\$_POST)) {\r\n".
						 "			\${$instancename} = \$this->model->{$classname};\r\n".
						 "			\$id= \${$instancename}->getId();\r\n".
						 "			\$isRedirect=true;\r\n".
						 self::uploadImgInEdit($instancename,$fieldInfo).
						 "			if (!empty(\$id)){\r\n".
						 "				\${$instancename}->update();\r\n".
						 "			}else{\r\n".
						 "				\$id=\${$instancename}->save();\r\n".
						 "			}\r\n".
						 "			if (\$isRedirect){\r\n".
						 "				\$this->redirect(\"{$instancename}\",\"view\",\"id=\$id\");\r\n".
						 "				exit;\r\n".
						 "			}\r\n".
						 "		}\r\n".
						 "		\${$instancename}Id=\$this->data[\"id\"];\r\n".
						 "		\${$instancename} = {$classname}::get_by_id(\${$instancename}Id);\r\n".
						 "		\$this->view->set(\"{$instancename}\",\${$instancename});\r\n";
				$text_area_fieldname=array();
				foreach ($fieldInfo as $fieldname=>$field)
				{
					if (self::columnIsTextArea($fieldname,$field["Type"]))
					{
						$text_area_fieldname[]="'".$fieldname."'";
					}
				}
				if (count($text_area_fieldname)==1){
					$result.="		//加载在线编辑器的语句要放在:\$this->view->viewObject[如果有这一句]之后。\r\n".
							 "		\$this->load_onlineditor({$text_area_fieldname[0]});\r\n";
				}else if (count($text_area_fieldname)>1){
					$fieldnames=implode(",", $text_area_fieldname);
					$result.="		//加载在线编辑器的语句要放在:\$this->view->viewObject[如果有这一句]之后。\r\n".
							 "		\$this->load_onlineditor(array({$fieldnames}));\r\n";
				}
				$result.="	}\r\n".
						 "	/**\r\n".
						 "	 * 删除{$table_comment}\r\n".
						 "	 */\r\n".
						 "	public function delete()\r\n".
						 "	{\r\n".
						 "		\${$instancename}Id=\$this->data[\"id\"];\r\n".
						 "		\$isDelete = {$classname}::deleteByID(\${$instancename}Id);\r\n".
						 "		\$this->redirect(\"{$instancename}\",\"lists\",\$this->data);\r\n".
						 "	}\r\n".
						 "}\r\n\r\n";
				$result.="?>";
				break;
			default:
				$result.="/**\r\n".
						 " +---------------------------------------<br/>\r\n".
						 " * 控制器:$table_comment<br/>\r\n".
						 " +---------------------------------------\r\n".
						 " * @category $category\r\n".
						 " * @package $package\r\n".
						 " * @author $author\r\n".
						 " */\r\n".
						 "class Action_$classname extends Action\r\n".
						 "{\r\n".
						 "	/**\r\n".
						 "	 * {$table_comment}列表\r\n".
						 "	 */\r\n".
						 "	public function lists()\r\n".
						 "	{\r\n".
						 "		\r\n".
						 "	}\r\n".
						 "	/**\r\n".
						 "	 * 查看{$table_comment}\r\n".
						 "	 */\r\n".
						 "	public function view()\r\n".
						 "	{\r\n".
						 "		\r\n".
						 "	}\r\n".
						 "	/**\r\n".
						 "	 * 编辑{$table_comment}\r\n".
						 "	 */\r\n".
						 "	public function edit()\r\n".
						 "	{\r\n".
						 "		\r\n".
						 "	}\r\n".
						 "	/**\r\n".
						 "	 * 删除{$table_comment}\r\n".
						 "	 */\r\n".
						 "	public function delete()\r\n".
						 "	{\r\n".
						 "		\r\n".
						 "	}\r\n".
						 "}\r\n\r\n";
				$result.="?>";
				break;
		}
		return $result;
	}

	/**
	 * 将表列定义转换成上传文件控制器Php文件定义的内容
	 * @param string $tablename 表名
	 * @param array $fieldInfo 表列信息列表
	 */
	public static function createBgActionUpload($tablename,$fieldInfo)
	{
		$table_comment=self::tableCommentKey($tablename);
		$classname = self::getClassname($tablename);
		$instancename=self::getInstancename($tablename);
		$result_upload = "	/**\r\n".
								 "	 * 上传数据对象:{$table_comment}数据文件\r\n".
								 "	 */\r\n".
								 "	public function upload{$classname}()\r\n".
								 "	{\r\n".
								 "		return self::ExtResponse(Manager_ExtService::{$instancename}Service()->import(\$_FILES));\r\n".
								 "	}\r\n\r\n";
		//批量上传图片
		foreach ($fieldInfo as $fieldname=>$field)
		{
			if (self::columnIsImage($fieldname,$field["Comment"]))
			{
				$fieldname_funcname=$fieldname;
				$fieldname_funcname{0}=strtoupper($fieldname_funcname);
				$imgs_upload= "	/**\r\n".
							  "	 * 批量上传{$table_comment}图片:$fieldname\r\n".
							  "	 */\r\n".
							  "	public function upload{$classname}{$fieldname_funcname}s()\r\n".
							  "	{\r\n".
							  "		return self::ExtResponse(Manager_ExtService::{$instancename}Service()->batchUploadImages(\$_FILES,\"upload_{$fieldname}_files\",\"{$classname}\",\"{$table_comment}\",\"$fieldname\"));\r\n".
							  "	}\r\n\r\n";
				$result_upload .= $imgs_upload;
			}
		}
		return $result_upload;
	}

	/**
	 * 将表列定义转换成核心控制器Php文件定义的内容
	 * @param string $tablename 表名
	 * @param array $fieldInfo 表列信息列表
	 */
	public static function createBgActionIndex($tablename,$fieldInfo)
	{
		$table_comment=self::tableCommentKey($tablename);
		$classname = self::getClassname($tablename);
		$instancename=self::getInstancename($tablename);
		$result ="	/**\r\n";
		$result.="	 * 控制器:$table_comment\r\n";
		$result.="	 */\r\n";
		$result.="	public function $instancename()\r\n";
		$result.="	{\r\n";
		$result.="		\$this->init();\r\n";
		$result.="		\$this->ExtDirectMode();\r\n";
		$result.="		\$this->ExtUpload();\r\n";

		if ((is_array(self::$relation_viewfield))&&(array_key_exists($classname, self::$relation_viewfield)))
		{
			$relationSpecs=self::$relation_viewfield[$classname];
		}
		$redundancy_table_fields=self::$redundancy_table_fields[$classname];
		$redundancy_fields=array();
		$isNeedTextarea=true;
		if ($redundancy_table_fields){
			foreach ($redundancy_table_fields as $redundancy_table_field) {
				$redundancy_fields=array_merge($redundancy_fields,$redundancy_table_field);
			}
		}
		foreach ($fieldInfo as $fieldname=>$field)
		{
			if (isset($relationSpecs)){
				if (array_key_exists($fieldname,$relationSpecs)){
					$relationShow=$relationSpecs[$fieldname];
					foreach ($relationShow as $key=>$value) {
						$fieldInfos=self::$fieldInfos[self::getTablename($key)];
						if (array_key_exists("parent_id",$fieldInfos)){
							$result.="		\$this->loadExtComponent(\"ComboBoxTree.js\");\r\n";
							break 2;
						}
					}
				}
			}
			if (!empty($redundancy_fields)){
				if (array_key_exists($fieldname, $redundancy_fields))$isNeedTextarea=false;
			}
		}
		if (Config_AutoCode::JSFILE_DIRECT_CORE){
			$result.="		\$this->loadExtJs('".Config_F::VIEW_CORE."/$instancename.js');\r\n";
		}else{
			$result.="		\$this->loadExtJs('$instancename/$instancename.js');\r\n";
		}

		if ($isNeedTextarea){
			$text_area_fieldname=array();
			foreach ($fieldInfo as $fieldname=>$field)
			{
				if (self::columnIsTextArea($fieldname,$field["Type"]))
				{
					if (!in_array("'".$fieldname."'", $text_area_fieldname)){
						$text_area_fieldname[]="'".$fieldname."'";
					}
				}

				if (Config_AutoCode::RELATION_VIEW_FULL)
				{
					if ((is_array(self::$relation_all))&&(array_key_exists($classname,self::$relation_all)))
					{
						$relationSpec=self::$relation_all[$classname];
						if (array_key_exists("has_many",$relationSpec))
						{
							$has_many=$relationSpec["has_many"];
							foreach ($has_many as $current_classname=>$value)
							{
								$tablename_relation=self::getTablename($current_classname);
								$fieldInfos_relation=self::$fieldInfos[$tablename_relation];
								foreach ($fieldInfos_relation as $fieldname_relation=>$fields_relation) {
									if (self::columnIsTextArea($fieldname_relation,$fields_relation["Type"]))
									{
										if (!in_array("'".$fieldname_relation."'", $text_area_fieldname)){
											$text_area_fieldname[].="'".$fieldname_relation."'";
										}
									}
								}
							}
						}
					}
				}
			}

			if (count($text_area_fieldname)==1){
				$result.="		\$this->load_onlineditor({$text_area_fieldname[0]});\r\n";
			}else if (count($text_area_fieldname)>1){
				$fieldnames=implode(",", $text_area_fieldname);
				$result.="		\$this->load_onlineditor(array({$fieldnames}));\r\n";
			}
		}
		$result.="	}\r\n\r\n";
		return $result;
	}

	/**
	 * 是否需要在编辑页面上传图片
	 * @param string $instancename 实体变量
	 * @param array $fieldInfo 表列信息列表
	 */
	private static function uploadImgInEdit($instancename,$fieldInfo)
	{
		$result="";
		$fieldNameAndComments=array();
		foreach ($fieldInfo as $fieldname=>$field)
		{
			$field_comment=$field["Comment"];
			if (contain($field_comment,"\r")||contain($field_comment,"\n"))
			{
				$field_comment=preg_split("/[\s,]+/", $field_comment);
				$field_comment=$field_comment[0];
			}
			$fieldNameAndComments[$fieldname]=$field_comment;
		}
		$img_fieldname=array();
		foreach ($fieldNameAndComments as $key=>$value) {
			$isImage =self::columnIsImage($key,$value);
			if ($isImage)
			{
				$img_fieldname[]=$key;
			}
		}

		if (count($img_fieldname>0)){
			foreach ($img_fieldname as $fieldname) {
				$result.="			if (!empty(\$_FILES)&&!empty(\$_FILES[\"{$fieldname}Upload\"][\"name\"])){\r\n".
						 "				\$result=\$this->uploadImg(\$_FILES,\"{$fieldname}Upload\",\"{$fieldname}\",\"$instancename\");\r\n".
						 "				if (\$result&&(\$result['success']==true)){\r\n".
						 "					if (array_key_exists('file_name',\$result))\${$instancename}->$fieldname = \$result['file_name'];\r\n".
						 "				}else{\r\n".
						 "					\$isRedirect=false;\r\n".
						 "					\$this->view->set(\"message\",\$result[\"msg\"]);\r\n".
						 "				}\r\n".
						 "			}\r\n";
			}
		}
		return $result;
	}

	/**
	 * 生成标准的增删改查模板Action文件需生成首页访问所有生成的链接
	 */
	private static function createModelIndexFile()
	{
		$category  = Gc::$appName;
		$package   = self::$package_front;
		if (self::$type==1)$package=self::$package_model;
		$author	= self::$author;
		$action_parent="Action";
		if (self::$type==1)$action_parent="ActionModel";
		$result="<?php\r\n".
				 "/**\r\n".
				 " +---------------------------------------<br/>\r\n".
				 " * 控制器:首页导航<br/>\r\n".
				 " +---------------------------------------\r\n".
				 " * @category $category\r\n".
				 " * @package $package\r\n".
				 " * @author $author\r\n".
				 " */\r\n".
				 "class Action_Index extends $action_parent\r\n".
				 "{\r\n".
				 "	/**\r\n".
				 "	 * 首页:网站所有页面列表\r\n".
				 "	 */\r\n".
				 "	public function index()\r\n".
				 "	{\r\n".
				 "		\r\n".
				 "	}\r\n".
				 "}\r\n\r\n".
				 "?>";
		self::saveDefineToDir(self::$action_dir_full,"Action_Index.php",$result);
	}

	/**
	 * 保存生成的代码到指定命名规范的文件中
	 * @param string $tablename 表名称
	 * @param string $definePhpFileContent 生成的代码
	 */
	private static function saveActionDefineToDir($tablename,$definePhpFileContent)
	{
		$classname=self::getClassname($tablename);
		$filename="Action_".$classname.".php";

		$relative_path=str_replace(self::$save_dir, "",self::$action_dir_full.$filename);

		switch (self::$type) {
			case 0:
				AutoCodePreviewReport::$action_front_files[$classname]=$relative_path;
				break;
			case 1:
				AutoCodePreviewReport::$action_model_files[$classname]=$relative_path;
				break;
		}
		return self::saveDefineToDir(self::$action_dir_full,$filename,$definePhpFileContent);
	}
}

?>
