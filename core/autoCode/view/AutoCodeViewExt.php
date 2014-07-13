<?php
/**
 +---------------------------------<br/>
 * 工具类:自动生成代码-使用ExtJs生成的表示层<br/>
 +---------------------------------<br/>
 * @category betterlife
 * @package core.autoCode.view
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCodeViewExt extends AutoCode
{
	/**
	 * 表示层所在的目录
	 */
	public static $view_core;
	/**
	 * 表示层Js文件所在的目录
	 */
	public static $view_js_package;
	/**
	 * 表示层完整的保存路径
	 */
	public static $view_dir_full;
	/**
	 * 菜单配置完整的保存路径
	 */
	public static $menuconfig_dir_full;
	/**
	 * 关系列显示发送ajax请求的配置完整的保存路径
	 */
	public static $ajax_dir_full;
	/**
	 * 查询过滤条件字段
	 */
	public static $filter_fieldnames=array();
	/**
	 * Ext "$relationStore="中关系库Store的定义
	 */
	public static $relationStore="";
	/**
	 * 设置必需的路径
	 */
	public static function pathset()
	{
		self::$app_dir="admin";
		self::$view_dir_full=self::$save_dir.self::$app_dir.DIRECTORY_SEPARATOR.Config_F::VIEW_VIEW.DIRECTORY_SEPARATOR.Gc::$self_theme_dir.DIRECTORY_SEPARATOR;
		self::$menuconfig_dir_full=self::$save_dir.self::$app_dir.DIRECTORY_SEPARATOR.self::$dir_src.DIRECTORY_SEPARATOR."view".DIRECTORY_SEPARATOR."menu".DIRECTORY_SEPARATOR;
		self::$ajax_dir_full=self::$save_dir.self::$app_dir.DIRECTORY_SEPARATOR.self::$dir_src.DIRECTORY_SEPARATOR."httpdata".DIRECTORY_SEPARATOR;
		self::$view_core=self::$view_dir_full.Config_F::VIEW_CORE.DIRECTORY_SEPARATOR;
		self::$view_js_package=self::$view_dir_full."js".DIRECTORY_SEPARATOR."ext".DIRECTORY_SEPARATOR;
	}

	/**
	 * 自动生成代码-使用ExtJs生成的表示层
	 */
	public static function AutoCode()
	{
		self::pathset();
		//加载生成实体数据对象的目录路径，以便生成代码中数据对象的ID名称
		$dirs_root=UtilFileSystem::getAllDirsInDriectory(self::$save_dir.DIRECTORY_SEPARATOR.Gc::$appName.DIRECTORY_SEPARATOR.self::$dir_src.DIRECTORY_SEPARATOR.self::$domain_dir.DIRECTORY_SEPARATOR);
		set_include_path(get_include_path().PATH_SEPARATOR.join(PATH_SEPARATOR, $dirs_root));
		$files = new AppendIterator();
		foreach ($dirs_root as $dir) {
			$tmp=new ArrayObject(UtilFileSystem::getAllFilesInDirectory($dir));
			if (isset($tmp)) $files->append($tmp->getIterator());
		}

		foreach ($files as $file) {
			Initializer::$coreFiles[Config_F::ROOT_CORE][basename($file,Initializer::SUFFIX_FILE_PHP)]=$file;
		}
		self::init();
		if (self::$isNoOutputCss) echo UtilCss::form_css()."\r\n";
		AutoCodeFoldHelper::foldEffectCommon("Content_51");
		echo "<font color='#FF0000'>采用ExtJs框架生成后端Js文件导出:</font></a>";
		echo '<div id="Content_51" style="display:none;">';
		foreach (self::$fieldInfos as $tablename=>$fieldInfo){
			$defineJsFileContent=self::tableToViewJsDefine($tablename,$fieldInfo);
			if (isset(self::$save_dir)&&!empty(self::$save_dir)&&isset($defineJsFileContent)){
				$jsName=self::saveJsDefineToDir($tablename,$defineJsFileContent);
				echo "生成导出完成:$tablename=>$jsName!<br/>";
			}else{
				echo $defineJsFileContent."<br/>";
			}
		}
		echo "</div><br>";
		AutoCodeFoldHelper::foldEffectCommon("Content_52");
		echo "<font color='#FF0000'>生成后端tpl模板显示文件导出:</font></a>";
		echo '<div id="Content_52" style="display:none;">';
		foreach (self::$fieldInfos as $tablename=>$fieldInfo){
			$defineTplFileContent=self::tableToViewTplDefine($tablename,$fieldInfo);
			if (isset(self::$save_dir)&&!empty(self::$save_dir)&&isset($defineTplFileContent)){
				$tplName=self::saveTplDefineToDir($tablename,$defineTplFileContent);
				echo "生成导出完成:$tablename=>$tplName!<br/>";
			}else{
				echo $defineTplFileContent."<br/>";
			}
		}
		echo '</div>';
		self::tableToAjaxPhpDefine();

		/**
		 * 需要在后端admin/src/view/menu目录下 菜单配置文件:menu.config.xml里添加的代码
		 */
		echo "<br/><font color='#FF0000'>[需要在后端admin/src/view/menu目录下菜单配置文件:menu.config.xml里添加没有的代码]</font><br/>";
		$section_content="";
		$appName=Gc::$appName;
		foreach(self::$tableList as $tablename){
			$table_comment=self::tableCommentKey($tablename);
			$instancename=self::getInstancename($tablename);
			$section_content.="        <menu name=\"$table_comment\" id=\"$instancename\" address=\"index.php?go=admin.$appName.{$instancename}\" />\r\n";
		}
		$filename="menu.config.xml";
		$output_section_content="<?xml version=\"1.0\" encoding=\"UTF-8\"?> \r\n".
						 "<menuGroups>\r\n".
						 "    <menuGroup id=\"navWebDev\" name=\"功能区\" iconCls=\"navdesign\" show=\"true\">\r\n".
						 $section_content.
						 "    </menuGroup> \r\n".
						 "</menuGroups>\r\n";
		self::saveDefineToDir(self::$menuconfig_dir_full,$filename,$output_section_content);
		echo  "新生成的menu.config.xml文件路径:<font color='#0000FF'>".self::$menuconfig_dir_full.$filename."</font><br/>";
		/*    $section_content=str_replace(" ","&nbsp;",$section_content);
			$section_content=str_replace("<","&lt;",$section_content);
			$section_content=str_replace(">","&gt;",$section_content);
			$section_content=str_replace("\r\n","<br />",$section_content);
			echo  $section_content;*/
	}

	/**
	 * 用户输入需求
	 */
	public static function UserInput($title=null,$inputArr=null)
	{
		parent::UserInput("使用ExtJs框架生成表示层【用于后台】的输出文件路径参数");
	}

	/**
	 * 生成关系列Ajax请求php文件。
	 */
	public static function tableToAjaxPhpDefine()
	{
		$isNeedCreate=false;
		if (is_array(self::$relation_viewfield)&&(count(self::$relation_viewfield)>0)) {
			foreach (self::$relation_viewfield as $relation_viewfield) {
				foreach ($relation_viewfield as $showClasses) {
					foreach ($showClasses as $key=>$value) {
						$fieldInfo=self::$fieldInfos[self::getTablename($key)];
						$key{0}=strtolower($key{0});
						$filename =$key.Config_F::SUFFIX_FILE_PHP;
						if (!file_exists(self::$ajax_dir_full.$filename)){
							$isNeedCreate=true;
							break 3;
						}
						if (array_key_exists("parent_id",$fieldInfo)){
							$filename =$key."Tree".Config_F::SUFFIX_FILE_PHP;
							if (!file_exists(self::$ajax_dir_full.$filename)){
								$isNeedCreate=true;
								break 3;
							}
						}
					}
				}
			}
		}

		if ($isNeedCreate){
			echo "<br />";
			AutoCodeFoldHelper::foldEffectCommon("Content_53");
			echo "<font color='#FF0000'>生成关系列Ajax请求php文件:</font></a>";
			echo '<div id="Content_53" style="display:none;">';
			foreach (self::$relation_viewfield as $relation_viewfield) {
				foreach ($relation_viewfield as $key=>$showClasses) {
					foreach ($showClasses as $key=>$value) {
						$key_i=$key;
						$key_i{0}=strtolower($key_i{0});
						$classname=$key;
						$classname{0}=strtolower($classname{0});

						$fieldInfo=self::$fieldInfos[self::getTablename($key)];
						if (array_key_exists("parent_id",$fieldInfo)){
							$filename =$key_i."Tree".Config_F::SUFFIX_FILE_PHP;
							if (!file_exists(self::$ajax_dir_full.$filename)){
								$realId=DataObjectSpec::getRealIDColumnName($classname);
								$showname=self::getShowFieldNameByClassname($key);
								$result="<?php \r\n".
										 "require_once (\"../../../../init.php\");\r\n".
										 "\$node=intval(\$_REQUEST[\"id\"]);\r\n".
										 "if (\$node){\r\n".
										 "    \$condition=array(\"parent_id\"=>\"\$node\");\r\n".
										 "}else{\r\n".
										 "    \$condition=array(\"parent_id\"=>'0');\r\n".
										 "}\r\n".
										 "\${$key_i}s={$key}::get(\$condition,\"$realId asc\");\r\n".
										 "echo \"[\";\r\n".
										 "if (!empty(\${$key_i}s)){\r\n".
										 "    \$trees=\"\";\r\n".
										 "    \$maxLevel={$key}::maxlevel();\r\n".
										 "    foreach (\${$key_i}s as \${$key_i}){\r\n".
										 "        \$trees.=\"{\r\n".
										 "            'text': '\${$key_i}->{$showname}',\r\n".
										 "            'id': '\${$key_i}->$realId',\r\n".
										 "            'level':'\${$key_i}->level',\";\r\n".
										 "        if (\${$key_i}->level==\$maxLevel){\r\n".
										 "            \$trees.=\"'leaf':true,'cls': 'file'\";\r\n".
										 "        }else{\r\n".
										 "            \$trees.=\"'cls': 'folder'\";\r\n".
										 "        }\r\n".
										 "        if (isset(\${$key_i}->countChild)){\r\n".
										 "            if (\${$key_i}->countChild==0){\r\n".
										 "                \$trees.=\",'leaf':true\";\r\n".
										 "            }\r\n".
										 "        }\r\n".
										 "        \$trees.=\"},\";\r\n".
										 "    }\r\n".
										 "    \$trees=substr(\$trees, 0, strlen(\$trees)-1);\r\n".
										 "    echo \$trees;\r\n".
										 "}\r\n".
										 "echo \"]\";\r\n".
										 "?>\r\n";
								$ajaxName=self::saveoAjaxPhpDefineToDir($filename,$result);
								echo "生成导出Ajax目录树服务类PHP文件完成:$tablename=>$ajaxName".Config_F::SUFFIX_FILE_PHP."!<br/>";
							}
						}

						$filename =$classname.Config_F::SUFFIX_FILE_PHP;
						if (!file_exists(self::$ajax_dir_full.$filename)){
							$result="<?php \r\n".
									 "require_once (\"../../../../init.php\");\r\n".
									 "\$pageSize=15;\r\n".
									 "\${$value}   = !empty(\$_REQUEST['query'])&&(\$_REQUEST['query']!=\"?\")&&(\$_REQUEST['query']!=\"？\") ? trim(\$_REQUEST['query']) : \"\";\r\n".
									 "\$condition=array();\r\n".
									 "if (!empty(\${$value})){\r\n".
									 "    \$condition[\"{$value}\"]=\" like '%\${$value}%'\";\r\n".
									 "}\r\n".
									 "\$start=0;\r\n".
									 "if (isset(\$_REQUEST['start'])){\r\n".
									 "    \$start=\$_REQUEST['start']+1;\r\n".
									 "}\r\n".
									 "\$limit=\$pageSize;\r\n".
									 "if (isset(\$_REQUEST['limit'])){\r\n".
									 "    \$limit=\$_REQUEST['limit'];\r\n".
									 "    \$limit= \$start+\$limit-1;\r\n".
									 "}\r\n".
									 "\$arr['totalCount']= {$key}::count(\$condition);\r\n".
									 "\$arr['{$key_i}s']    = {$key}::queryPage(\$start,\$limit,\$condition);\r\n".
									 "echo json_encode(\$arr);\r\n".
									 "?>\r\n";
							$key{0}=strtolower($key{0});
							$filename =$key.Config_F::SUFFIX_FILE_PHP;
							$ajaxName=self::saveoAjaxPhpDefineToDir($filename,$result);
							echo "生成导出Ajax服务类PHP文件完成:$tablename=>$ajaxName".Config_F::SUFFIX_FILE_PHP."!<br/>";
						}
					}
				}
			}
			echo '</div>';
		}
	}

	/**
	 * 将表列定义转换成使用ExtJs生成的表示层Js文件定义的内容
	 * @param string $tablename 表名
	 * @param string $appName_alias 应用别名
	 * @param array $fieldInfo 表列信息列表
	 */
	public static function tableToViewJsDefine($tablename,$fieldInfo)
	{
		$appName_alias=Gc::$appName_alias;
		$appName_alias=ucfirst($appName_alias);
		$appName=Gc::$appName;
		$table_comment=self::tableCommentKey($tablename);
		$classname=self::getClassname($tablename);
		$instancename=self::getInstancename($tablename);
		$appName=ucfirst($appName);
		//Ext "store" 中包含的fields
		$storeInfo=self::model_fields($tablename,$classname,$instancename,$fieldInfo);
		$fields=$storeInfo['fields'];
		//Ext "$relationStore="中关系库Store的定义
		$relationStore=$storeInfo['relationStore'];
		$relationClassesView=$storeInfo['relationClassesView'];
		$relationViewAdds=$storeInfo['relationViewAdds'];
		$relationViewGrids=$storeInfo['relationViewGrids'];
		$viewRelationDoSelect=$storeInfo['viewRelationDoSelect'];
		$relationViewGridInit=$storeInfo['relationViewGridInit'];
		$relationM2mMenu=$storeInfo['relationM2mMenu'];
		$relationM2mMenuShowHide=$storeInfo['relationM2mMenuShowHide'];
		$relationM2mRowSelect=$storeInfo['relationM2mRowSelect'];
		$relationM2mRowSelectElse=$storeInfo['relationM2mRowSelectElse'];
		$relationM2mShowHide=$storeInfo['relationM2mShowHide'];
		$relationM2mRunningWindow=$storeInfo['relationM2mRunningWindow'];

		//获取Ext "EditWindow"里items的fieldLabels
		$editWindowVars=self::model_fieldLables($tablename,$appName_alias,$classname,$fieldInfo);
		$fieldLabels=$editWindowVars["fieldLabels"];
		$password_Add=$editWindowVars["password_Add"];
		$password_update=$editWindowVars["password_update"];
		$isFileUpload=array_key_exists("isFileUpload", $editWindowVars) ? $editWindowVars["isFileUpload"]:"";

		$treeLevelVisible_Add   =$editWindowVars["treeLevelVisible_Add"];
		$treeLevelVisible_Update=$editWindowVars["treeLevelVisible_Update"];

		$textarea_Vars=self::model_textareaOnlineEditor($appName_alias,$classname,$instancename,$fieldInfo);
		$tableFieldIdName=$textarea_Vars["tableFieldIdName"];
		$textareaOnlineditor_Replace=$textarea_Vars["textareaOnlineditor_Replace"];
		$textareaOnlineditor_Add=$textarea_Vars["textareaOnlineditor_Add"];
		$textareaOnlineditor_Update=$textarea_Vars["textareaOnlineditor_Update"];
		$textareaOnlineditor_Save=$textarea_Vars["textareaOnlineditor_Save"];
		$textareaOnlineditor_Reset=$textarea_Vars["textareaOnlineditor_Reset"];
		$textareaOnlineditor_Init=$textarea_Vars["textareaOnlineditor_Init"];
		$textareaOnlineditor_Init_func=$textarea_Vars["textareaOnlineditor_Init_func"];

		//Ext "Tabs" 中"onAddItems"包含的viewdoblock
		$viewdoblock=self::model_viewblock($tablename,$classname,$fieldInfo);
		//Ext "Grid" 中包含的columns
		$columns=self::model_columns($tablename,$classname,$fieldInfo);

		$filters=self::model_filters($appName_alias,$classname,$instancename,$fieldInfo);
		//Ext "Grid" 中"tbar"包含的items中的items
		$filterFields   =$filters["filterFields"];
		//重置语句
		$filterReset    =$filters["filterReset"];
		//查询中的语句
		$filterdoSelect =$filters["filterdoSelect"];

		$upload_mixed   =self::model_upload($appName_alias,$classname,$instancename,$fieldInfo);
		//批量上传图片文件菜单
		$menu_uploadImg =$upload_mixed["menu_uploadImg"];
		//打开批量上传图片窗口
		$openBatchUploadImagesWindow   =$upload_mixed["openBatchUploadImagesWindow"];
		$batchUploadImagesWinow        =$upload_mixed["batchUploadImagesWinow"];
		$result="";
		$realId=DataObjectSpec::getRealIDColumnName($classname);
		require("jsmodel".DIRECTORY_SEPARATOR."includemodeljs.php");
		$result.=$jsContent;
		return $result;
	}

	/**
	 * 获取Ext "Store"里的fields
	 * @param string $tablename 表名
	 * @param string $classname 数据对象类名
	 * @param string $instancename 实体变量
	 * @param array $fieldInfo 表列信息列表
	 * @param array $isHaveRelation 是否需要关系显示
	 */
	private static function model_fields($tablename,$classname,$instancename,$fieldInfo,$isHaveRelation=true)
	{
		$fields="";//Ext "store" 中包含的fields
		$relationStore="";//Ext "$relationStore="中关系库Store的定义
		$relationClassesView="";//Ext 关系表的显示定义
		$isTreelevelStoreHad=false;
		self::$relationStore="";
		foreach ($fieldInfo as $fieldname=>$field)
		{
			if (self::isNotColumnKeywork($fieldname))
			{
				$datatype=self::comment_type($field["Type"]);
				$field_comment=$field["Comment"];
				if (contains($field_comment,array("日期","时间")))
				{
					$datatype='date';
				}
				if ($datatype=='enum'){
					$datatype='string';
					$fields.="                {name: '{$fieldname}Show',type: '".$datatype."'},\r\n";
				}
				$fields.="                {name: '$fieldname',type: '".$datatype."'";
				if ($datatype=='date')
				{
					$fields.=",dateFormat:'Y-m-d H:i:s'";
				}
				$fields.="},\r\n";
				if (self::columnIsTextArea($fieldname,$field["Type"])){
					$fields.="                {name: '{$fieldname}Show',type:'string'},\r\n";
				}

				if (is_array(self::$relation_viewfield)&&(count(self::$relation_viewfield)>0))
				{
					if (array_key_exists($classname,self::$relation_viewfield))
					{
						$relationSpecs=self::$relation_viewfield[$classname];
						if (array_key_exists($fieldname,$relationSpecs))
						{
							$relationShow=$relationSpecs[$fieldname];
							foreach ($relationShow as $key=>$value) {
								$realId=DataObjectSpec::getRealIDColumnName($key);
								if (empty($realId))$realId=$fieldname;
								if ((!array_key_exists($value,$fieldInfo))||($classname==$key)){
									$show_fieldname=$value;
									if ($realId!=$fieldname){
										if (contain($fieldname,"_id")){
											$fieldname=str_replace("_id","",$fieldname);
										}
										$show_fieldname.="_".$fieldname;
									}
									if ($show_fieldname=="name"){
										$show_fieldname= strtolower($key)."_".$value;
									}
									if (!array_key_exists("$show_fieldname",$fieldInfo)){
										$fields.="                {name: '$show_fieldname',type: 'string'},\r\n";
									}
								}else{
									if ($value=="name"){
										$show_fieldname= strtolower($key)."_".$value;
										if (!array_key_exists("$show_fieldname",$fieldInfo)){
											$fields.="                {name: '$show_fieldname',type: 'string'},\r\n";
										}
									}
								}
								$relation_classcomment=self::relation_classcomment(self::$class_comments[$key]);

								$fieldInfo_relationshow=self::$fieldInfos[self::getTablename($key)];
								$key{0}=strtolower($key{0});
								if (!$isTreelevelStoreHad){
									if (array_key_exists("parent_id",$fieldInfo_relationshow)){
										$fields.="                {name: '{$key}ShowAll',type: 'string'},\r\n";
										$isTreelevelStoreHad=true;
									}
								}
								if ((!$isTreelevelStoreHad)&&(!contain(self::$relationStore,"{$key}StoreForCombo"))){
									$showValue=$value;
									if ($value=="name") $showValue=strtolower($key)."_".$value;
									$relationStore_combo=",\r\n".
													"    /**\r\n".
													"     * {$relation_classcomment}\r\n".
													"     */\r\n".
													"    {$key}StoreForCombo:new Ext.data.Store({\r\n".
													"        proxy: new Ext.data.HttpProxy({\r\n".
													"            url: 'home/admin/src/httpdata/{$key}.php'\r\n".
													"        }),\r\n".
													"        reader: new Ext.data.JsonReader({\r\n".
													"            root: '{$key}s',\r\n".
													"            autoLoad: true,\r\n".
													"            totalProperty: 'totalCount',\r\n".
													"            idProperty: '$realId'\r\n".
													"        }, [\r\n".
													"            {name: '$realId', mapping: '$realId'},\r\n";
									if (array_key_exists("level",$fieldInfo_relationshow)){
										$showLevel=strtolower($key)."_level";
										$relationStore_combo.="            {name: '$showLevel', mapping: 'level'},\r\n";
									}
									$relationStore_combo.="            {name: '$showValue', mapping: '$value'}\r\n".
													"        ])\r\n".
													"    })";
									$relationStore.=$relationStore_combo;
									self::$relationStore.=$relationStore_combo;
								}
							}
						}
					}
				}
			}
		}

		if (array_key_exists($classname, self::$relation_all))$relationSpec=self::$relation_all[$classname];
		if (isset($relationSpec)&&is_array($relationSpec)&&(count($relationSpec)>0))
		{
			if (array_key_exists("has_many",$relationSpec))
			{
				$has_many=$relationSpec["has_many"];
				foreach (array_keys($has_many) as $key)
				{
					if (self::isMany2ManyByClassname($key))
					{
						$tablename_m2m=self::getTablename($key);
						$fieldInfo_m2m=self::$fieldInfos[$tablename_m2m];
						$belong_class="";
						foreach (array_keys($fieldInfo_m2m) as $fieldname)
						{
							if (!self::isNotColumnKeywork($fieldname))continue;
							if ($fieldname==self::keyIDColumn($key))continue;
							if (contain($fieldname,"_id")){
								$to_class=str_replace("_id", "", $fieldname);
								$to_class{0}=strtoupper($to_class{0});
								if (class_exists($to_class)){
									if ($to_class!=$classname){
										$belong_class=$to_class;
									}
								}
							}
						}

						$tablename_belong=self::getTablename($belong_class);
						$belong_instance_name=self::getInstancename($tablename_belong);
						$fields.="                {name: '{$belong_instance_name}Str',type: 'string'},\r\n";
					}
				}
			}
		}

		$fields=substr($fields,0,strlen($fields)-3);
		$result['fields']=$fields;
		$result['relationStore_onlyForFieldLabels']=$relationStore;
		if ($isHaveRelation){
			$relationViewDefine=self::relationViewDefine($tablename,$classname,$instancename,$relationStore);
		}
		$relationStore=$relationViewDefine['relationStore'];
		$relationClassesView=$relationViewDefine['one2many'];
		$relationViewAdds=$relationViewDefine['relationViewAdds'];
		$relationViewGrids=$relationViewDefine['relationViewGrids'];
		$viewRelationDoSelect=$relationViewDefine['viewRelationDoSelect'];
		$relationViewGridInit=$relationViewDefine['relationViewGridInit'];
		$relationM2mMenu=$relationViewDefine['m2mMenu'];
		$relationM2mRowSelect=$relationViewDefine['m2mRowSelect'];
		$relationM2mRowSelectElse=$relationViewDefine['m2mRowSelectElse'];
		$relationM2mMenuShowHide=$relationViewDefine['m2mMenuShowHide'];
		$relationM2mShowHide=$relationViewDefine['m2mShowHide'];
		$relationM2mRunningWindow=$relationViewDefine['m2mRunningWindow'];
		$result['relationStore']=$relationStore;
		$result['relationClassesView']=$relationClassesView;
		$result['relationViewAdds']=$relationViewAdds;
		$result['relationViewGrids']=$relationViewGrids;
		$result['viewRelationDoSelect']="\r\n".$viewRelationDoSelect;
		$result['relationViewGridInit']="\r\n".$relationViewGridInit;
		$result['relationM2mMenu']=$relationM2mMenu;
		$result['relationM2mRowSelect']=$relationM2mRowSelect;
		$result['relationM2mRowSelectElse']=$relationM2mRowSelectElse;
		$result['relationM2mMenuShowHide']=$relationM2mMenuShowHide;
		$result['relationM2mShowHide']=$relationM2mShowHide;
		$result['relationM2mRunningWindow']=$relationM2mRunningWindow;
		return $result;
	}

	/**
	 * 关系显示定义
	 * @param string $tablename 表名
	 * @param string $classname 数据对象类名
	 * @param string $instancename 实体变量
	 * @param string $relationStore Ext "$relationStore="中关系库Store的定义
	 */
	private static function relationViewDefine($tablename,$classname,$instancename,$relationStore)
	{
		if (array_key_exists($classname, self::$relation_all))$relationSpec=self::$relation_all[$classname];
		$relationClassesView="";
		$appName_alias=Gc::$appName_alias;
		$relationViewAdds="";
		$relationViewGrids="";
		$viewRelationDoSelect="";
		$relationViewGridInit="";
		//一选多代码生成
		$result=array(
			'm2mMenu'=>'',
			'm2mRowSelect'=>'',
			'm2mRowSelectElse'=>'',
			'm2mShowHide'=>'',
			'm2mRunningWindow'=>'',
			'm2mMenuShowHide'=>''
		);
		//导出一对多关系规范定义(如果存在)

		if (isset($relationSpec)&&is_array($relationSpec)&&(count($relationSpec)>0))
		{
			if (array_key_exists("has_many",$relationSpec))
			{
				$has_many=$relationSpec["has_many"];
				foreach ($has_many as $key=>$value)
				{
					$current_classname=$key;
					$key{0}=strtolower($key{0});
					$tablename=self::getTablename($current_classname);
					if (empty($tablename))continue;
					$current_instancename=self::getInstancename($tablename);

					$relation_classcomment=self::relation_classcomment(self::$class_comments[$current_classname]);
					if (self::isMany2ManyShowHasMany($current_classname))
					{
						$relationViewAdds.="                    {title: '$relation_classcomment',iconCls:'tabs',tabWidth:150,\r\n".
										   "                     items:[$appName_alias.$classname.View.Running.{$current_instancename}Grid]\r\n".
										   "                    },\r\n";
						$relationViewGrids.="        /**\r\n".
											"         * 当前{$relation_classcomment}Grid对象\r\n".
											"         */\r\n".
											"        {$current_instancename}Grid:null,\r\n";
						$viewRelationDoSelect.="            $appName_alias.$classname.View.Running.{$current_instancename}Grid.doSelect{$current_classname}();\r\n";
						$relationViewGridInit.="                $appName_alias.$classname.View.Running.{$current_instancename}Grid=new $appName_alias.$classname.View.{$current_classname}View.Grid();\r\n";
					}
					if (!contain($relationStore,"{$key}Store:"))
					{
						$fieldInfo=self::$fieldInfos[$tablename];
						$fields_relation="";
						foreach ($fieldInfo as $fieldname=>$field)
						{
							if (!self::isNotColumnKeywork($fieldname))continue;
							$datatype=self::comment_type($field["Type"]);
							if ($fieldname==self::keyIDColumn($current_classname))
							{
								$fields_relation.="                {name: '$fieldname',type: '$datatype'},\r\n";
								continue;
							}
							$field_comment=$field["Comment"];
							$isMoreShowAll=false;
							if (contain($fieldname,"_id")){
								$maybe_classname=str_replace("_id","",$fieldname);
								$maybe_classname{0}=strtoupper($maybe_classname{0});
								if (class_exists($maybe_classname))
								{
                                    $fields_relation.="                {name: '$fieldname',type: '".$datatype."'},\r\n";
									$fieldname=self::getShowFieldNameByClassname($maybe_classname);
									if ($fieldname=="name")$fieldname=strtolower($maybe_classname)."_".$fieldname;
									$datatype="string";
									$fieldInfo_maybe=self::$fieldInfos[self::getTablename($maybe_classname)];
									if (array_key_exists("parent_id",$fieldInfo_maybe)&&array_key_exists("level",$fieldInfo_maybe)){
										$isMoreShowAll=true;
									}
								}
							}
							if (contains($field_comment,array("日期","时间")))
							{
								$datatype='date';
							}
							$datatype_origin=$datatype;
							if ($datatype=='enum'){
								$datatype='string';
							}
							$fields_relation.="                {name: '$fieldname',type: '".$datatype."'";
							if ($datatype=='date')
							{
								$fields_relation.=",dateFormat:'Y-m-d H:i:s'";
							}
							$fields_relation.="},\r\n";
							if ($datatype_origin=='enum'){
								$fieldname=$fieldname."Show";
								$fields_relation.="                {name: '$fieldname',type: '".$datatype."'},\r\n";
							}
							if ($isMoreShowAll){
								$i_name=$maybe_classname;
								$i_name{0}=strtolower($i_name{0});
								$fields_relation.="                {name: '{$i_name}ShowAll',type: '".$datatype."'},\r\n";
							}
						}
						$fields_relation=substr($fields_relation,0,strlen($fields_relation)-3);
						if (self::isMany2ManyShowHasMany($current_classname))
						{
							$relation_classcomment=self::relation_classcomment(self::$class_comments[$current_classname]);
							$relationStore.=",\r\n".
											"    /**\r\n".
											"     * {$relation_classcomment}\r\n".
											"     */\r\n".
											"    {$key}Store:new Ext.data.Store({\r\n".
											"        reader: new Ext.data.JsonReader({\r\n".
											"            totalProperty: 'totalCount',\r\n".
											"            successProperty: 'success',\r\n".
											"            root: 'data',remoteSort: true,\r\n".
											"            fields : [\r\n".
											"$fields_relation\r\n".
											"            ]}\r\n".
											"        ),\r\n".
											"        writer: new Ext.data.JsonWriter({\r\n".
											"            encode: false \r\n".
											"        }),\r\n".
											"        listeners : {\r\n".
											"            beforeload : function(store, options) {\r\n".
											"                if (Ext.isReady) {\r\n".
											"                    if (!options.params.limit)options.params.limit=$appName_alias.$classname.Config.PageSize;\r\n".
											"                    Ext.apply(options.params, $appName_alias.$classname.View.Running.{$current_instancename}Grid.filter);//保证分页也将查询条件带上\r\n".
											"                }\r\n".
											"            }\r\n".
											"        }\r\n".
											"    })";
						}
					}
					if (!contain($relationClassesView,"{$current_classname}View"))
					{
						include("jsmodel".DIRECTORY_SEPARATOR."many2many.php");
						$result['m2mMenu'].=$jsMany2ManyMenu;
						if(isset($jsMany2ManyRowSelect))$result['m2mRowSelect'].=$jsMany2ManyRowSelect;
						if(isset($m2mRowSelectElse))$result['m2mRowSelectElse'].=$jsMany2ManyRowSelectElse;
						$result['m2mShowHide'].=$jsMany2ManyShowHide;
						$result['m2mRunningWindow'].=$jsMany2ManyRunningWindow;
						if(isset($jsMany2ManyMenuShowHide))$result['m2mMenuShowHide'].=$jsMany2ManyMenuShowHide;
						$relationClassesView.=$jsMany2ManyContent;
						$table_comment12n=self::tableCommentKey($tablename);
						$realId=DataObjectSpec::getRealIDColumnName($classname);
						$columns_relation="";
						$fieldInfo=self::$fieldInfos[$tablename];
						foreach ($fieldInfo as $fieldname=>$field)
						{
							if (!self::isNotColumnKeywork($fieldname))
							{
							   continue;
							}
							if ($fieldname==self::keyIDColumn($current_classname))
							{
								$columns_relation.="                            {header : '标识',dataIndex : '{$fieldname}',hidden:true},\r\n";
								continue;
							}
							if ($realId==$fieldname) continue;

							$field_comment=$field["Comment"];
							$field_comment=self::columnCommentKey($field_comment,$fieldname);
							$datatype=self::comment_type($field["Type"]);
							$isMoreShowAll=false;
							if (contain($fieldname,"_id")){
								$maybe_classname=str_replace("_id","",$fieldname);
								$maybe_classname{0}=strtoupper($maybe_classname{0});
								if (class_exists($maybe_classname))
								{
									$fieldname=self::getShowFieldNameByClassname($maybe_classname);
									if ($fieldname=="name")$fieldname=strtolower($maybe_classname)."_".$fieldname;
									$fieldInfo_maybe=self::$fieldInfos[self::getTablename($maybe_classname)];
									if (array_key_exists("parent_id",$fieldInfo_maybe)&&array_key_exists("level",$fieldInfo_maybe)){
										$isMoreShowAll=true;
									}
								}
							}
							if ($datatype=='enum'){
								$fieldname=$fieldname."Show";
							}
							$columns_relation.="                            {header : '$field_comment',dataIndex : '{$fieldname}'";
							if (($datatype=='date')||contains($field_comment,array("日期","时间")))
							{
								$columns_relation.=",renderer:Ext.util.Format.dateRenderer('Y-m-d')";
							}

							$column_type=self::column_type($field["Type"]);
							if ($column_type=='bit'){
								$columns_relation.=",renderer:function(value){if (value == true) {return \"是\";}else{return \"否\";}}";
							}
							$columns_relation.="},\r\n";
							if ($isMoreShowAll){
								$i_name=$maybe_classname;
								$i_name{0}=strtolower($i_name{0});
								$columns_relation.="                            {header : '{$field_comment}[全]',dataIndex :'{$i_name}ShowAll'},\r\n";
							}
						}
						$columns_relation=substr($columns_relation,0,strlen($columns_relation)-3);
						include("jsmodel".DIRECTORY_SEPARATOR."one2many.php");
						$relationClassesView.=$jsOne2ManyContent;
					}
				}
			}
		}

		$result['relationStore']=$relationStore;
		if (empty($relationViewAdds)){
			$relationViewAdds.="                    {title: '其他',iconCls:'tabs'}";
		}else{
			$relationViewAdds=substr($relationViewAdds,0,strlen($relationViewAdds)-3);
		}
		$relationViewAdds="                this.add(\r\n".
						  $relationViewAdds."\r\n".
						  "                );";
		$result['one2many']=$relationClassesView;
		$result['relationViewAdds']="\r\n".$relationViewAdds;
		$relationViewGrids=substr($relationViewGrids,0,strlen($relationViewGrids)-2);
		$result['relationViewGrids']="\r\n".$relationViewGrids;
		$viewRelationDoSelect=substr($viewRelationDoSelect,0,strlen($viewRelationDoSelect)-2);
		$result['viewRelationDoSelect']=$viewRelationDoSelect;
		$relationViewGridInit=substr($relationViewGridInit,0,strlen($relationViewGridInit)-2);
		$result['relationViewGridInit']=$relationViewGridInit;
		return $result;
	}

	/**
	 * 获取Ext "EditWindow"里items的fieldLabels
	 * @param string $tablename 表名
	 * @param string $appName_alias 应用别名
	 * @param string $classname 数据对象类名
	 * @param array $fieldInfo 表列信息列表
	 * @param array $relationIgnoreID 在一对多关系Grid里的EditWindow中需忽视自己的标识
	 * @param string $blank_pre 空格字符串
	 */
	private static function model_fieldLables($tablename,$appName_alias,$classname,$fieldInfo,$relationIgnoreID=null,$blank_pre="")
	{
		$result=array();
		$fieldLabels="";//Ext "EditWindow"里items的fieldLabels
		$treeLevelVisible_Add   ="";
		$treeLevelVisible_Update="";
		$isRedundancyCurrentHad=false;
		$redundancy_table_fields=self::$redundancy_table_fields[$classname];
		$relationStore="";

		$password_Add="";
		$password_update="";

		foreach ($fieldInfo as $fieldname=>$field)
		{
			if ($redundancy_table_fields){
				if (!$isRedundancyCurrentHad){
					$redundancy_fields=array();
					foreach ($redundancy_table_fields as $redundancy_table_field) {
						$redundancy_fields=array_merge($redundancy_fields,$redundancy_table_field);
					}
					$isRedundancyCurrentHad=true;
				}
				if (array_key_exists($fieldname, $redundancy_fields))continue;
			}

			if (isset($ignord_field)&&($ignord_field==$fieldname)){
				continue;
			}

			if (!empty($relationIgnoreID)&&($fieldname==$relationIgnoreID)){
				$fieldLabels.=$blank_pre."                            {xtype: 'hidden',name : '$fieldname',ref:'../$fieldname'},\r\n";
				continue;
			}

			if (self::isNotColumnKeywork($fieldname))
			{
				if (is_array(self::$relation_viewfield)&&(count(self::$relation_viewfield)>0))
				{
					if (array_key_exists($classname,self::$relation_viewfield))
					{
						$relationSpecs=self::$relation_viewfield[$classname];
						if (array_key_exists($fieldname,$relationSpecs)){
							$relationShow=$relationSpecs[$fieldname];
							foreach ($relationShow as $key=>$value) {
								if ((!array_key_exists($value,$fieldInfo))||($classname==$key)){
									$field_comment=$field["Comment"];
									$field_comment=self::columnCommentKey($field_comment,$fieldname);
								}else{
									$field_comment=self::$fieldInfos[self::getTablename($key)][$value]["Comment"];
									$field_comment=self::columnCommentKey($field_comment,$value);
									if ($field_comment=="名称"){
										$field_comment=$field["Comment"];
										$field_comment=self::columnCommentKey($field_comment,$fieldname);
									}
									$ignord_field=$value;
								}
								$realId=DataObjectSpec::getRealIDColumnName($key);
								if (empty($realId))$realId=$fieldname;
								//避免name和本表的name冲突可能
								if ($value=="name") $value=strtolower($key)."_".$value;
								$show_name_diff=$value;

								if ($realId!=$fieldname){
									if (contain($fieldname,"_id")){
										$fieldname_modify=str_replace("_id","",$fieldname);
									}
									$show_name_diff.="_".$fieldname_modify;
								}
								$fieldInfo_relationshow=self::$fieldInfos[self::getTablename($key)];
								$current_classname=$key;
								$key{0}=strtolower($key{0});
								if (array_key_exists("parent_id",$fieldInfo_relationshow)){
									$treeLevelVisible_Add="\r\n".
														  $blank_pre."            $appName_alias.$classname.View.Running.edit_window.{$key}comp.btnModify.setVisible(false);\r\n".
														  $blank_pre."            $appName_alias.$classname.View.Running.edit_window.{$key}comp.{$key}_name.setVisible(true);\r\n".
														  $blank_pre."            $appName_alias.$classname.View.Running.edit_window.{$key}comp.{$key}ShowLabel.setVisible(false);\r\n".
														  $blank_pre."            $appName_alias.$classname.View.Running.edit_window.{$key}comp.{$key}ShowValue.setVisible(false);\r\n";
									$treeLevelVisible_Update="\r\n".
															 $blank_pre."            if (this.getSelectionModel().getSelected().data.{$key}ShowAll){\r\n".
															 $blank_pre."                $appName_alias.$classname.View.Running.edit_window.{$key}comp.btnModify.setVisible(true);\r\n".
															 $blank_pre."                $appName_alias.$classname.View.Running.edit_window.{$key}comp.{$key}_name.setVisible(false);\r\n".

															 $blank_pre."                $appName_alias.$classname.View.Running.edit_window.{$key}comp.{$key}ShowLabel.setVisible(true);\r\n".
															 $blank_pre."                $appName_alias.$classname.View.Running.edit_window.{$key}comp.{$key}ShowValue.setVisible(true);\r\n".
															 $blank_pre."            }else{\r\n".
															 $blank_pre."                $appName_alias.$classname.View.Running.edit_window.{$key}comp.btnModify.setVisible(false);\r\n".
															 $blank_pre."                $appName_alias.$classname.View.Running.edit_window.{$key}comp.{$key}_name.setVisible(true);\r\n".
															 $blank_pre."                $appName_alias.$classname.View.Running.edit_window.{$key}comp.{$key}ShowLabel.setVisible(false);\r\n".
															 $blank_pre."                $appName_alias.$classname.View.Running.edit_window.{$key}comp.{$key}ShowValue.setVisible(false);\r\n".
															 $blank_pre."            }\r\n";
									$fieldLabels.=$blank_pre."                            {xtype: 'hidden',name : '$fieldname',ref:'../$fieldname'},\r\n".
												  $blank_pre."                            {\r\n".
												  $blank_pre."                                  xtype: 'compositefield',ref: '../{$key}comp',\r\n".
												  $blank_pre."                                  items: [\r\n".
												  $blank_pre."                                      {\r\n".
												  $blank_pre."                                          xtype:'combotree', fieldLabel:'{$field_comment}',ref:'{$key}_name',name: '{$key}_name',grid:this,\r\n".
												  $blank_pre."                                          emptyText: '请选择{$field_comment}',canFolderSelect:true,flex:1,editable:false,\r\n".
												  $blank_pre."                                          tree: new Ext.tree.TreePanel({\r\n".
												  $blank_pre."                                              dataUrl: 'home/admin/src/httpdata/{$key}Tree.php',\r\n".
												  $blank_pre."                                              root: {nodeType: 'async'},border: false,rootVisible: false,\r\n".
												  $blank_pre."                                              listeners: {\r\n".
												  $blank_pre."                                                  beforeload: function(n) {if (n) {this.getLoader().baseParams.id = n.attributes.id;}}\r\n".
												  $blank_pre."                                              }\r\n".
												  $blank_pre."                                          }),\r\n".
												  $blank_pre."                                          onSelect: function(cmb, node) {\r\n".
												  $blank_pre."                                              this.grid.{$fieldname}.setValue(node.attributes.id);\r\n".
												  $blank_pre."                                              this.setValue(node.attributes.text);\r\n".
												  $blank_pre."                                          }\r\n".
												  $blank_pre."                                      },\r\n".
												  $blank_pre."                                      {xtype:'button',text : '修改{$field_comment}',ref: 'btnModify',iconCls : 'icon-edit',\r\n".
												  $blank_pre."                                       handler:function(){\r\n".
												  $blank_pre."                                           this.setVisible(false);\r\n".
												  $blank_pre."                                           this.ownerCt.ownerCt.{$key}_name.setVisible(true);\r\n".
												  $blank_pre."                                           this.ownerCt.ownerCt.{$key}ShowLabel.setVisible(true);\r\n".
												  $blank_pre."                                           this.ownerCt.ownerCt.{$key}ShowValue.setVisible(true);\r\n".
												  $blank_pre."                                           this.ownerCt.ownerCt.doLayout();\r\n".
												  $blank_pre."                                      }},\r\n".
												  $blank_pre."                                      {xtype:'displayfield',value:'所选{$field_comment}:',ref: '{$key}ShowLabel'},{xtype:'displayfield',name:'{$key}ShowAll',flex:1,ref: '{$key}ShowValue'}]\r\n".
												  $blank_pre."                            },\r\n";
								}else{
									$show_name_diff_name= $show_name_diff;
									if ($show_name_diff=="title")$show_name_diff=$key."_".$show_name_diff;
									$dorefresh="";
									if (Config_AutoCode::COMBO_REFRESH){
										$dorefresh=$blank_pre."                                 listeners:{\r\n".
												   $blank_pre."                                     'beforequery': function(event){delete event.combo.lastQuery;}\r\n".
												   $blank_pre."                                 },\r\n";
									}
									$fieldLabels.=$blank_pre."                            {xtype: 'hidden',name : '$fieldname',ref:'../$fieldname'},\r\n".
												  $blank_pre."                            {\r\n".
												  $blank_pre."                                 fieldLabel : '{$field_comment}',xtype: 'combo',name : '$show_name_diff_name',ref : '../$show_name_diff',\r\n".
												  $blank_pre."                                 store:$appName_alias.$classname.Store.{$key}StoreForCombo,emptyText: '请选择{$field_comment}',itemSelector: 'div.search-item',\r\n".
												  $blank_pre."                                 loadingText: '查询中...',width: 570, pageSize:$appName_alias.$classname.Config.PageSize,\r\n".
												  $blank_pre."                                 displayField:'$value',grid:this,\r\n".
												  $blank_pre."                                 mode: 'remote',  editable:true,minChars: 1,autoSelect :true,typeAhead: false,\r\n".
												  $blank_pre."                                 forceSelection: true,triggerAction: 'all',resizable:false,selectOnFocus:true,\r\n".
												  $blank_pre."                                 tpl:new Ext.XTemplate(\r\n".
												  $blank_pre."                                     '<tpl for=\".\"><div class=\"search-item\">',\r\n".
												  $blank_pre."                                         '<h3>{{$value}}</h3>',\r\n".
												  $blank_pre."                                     '</div></tpl>'\r\n".
												  $blank_pre."                                 ),\r\n".$dorefresh.
												  $blank_pre."                                 onSelect:function(record,index){\r\n".
												  $blank_pre."                                     if(this.fireEvent('beforeselect', this, record, index) !== false){\r\n".
												  $blank_pre."                                        this.grid.$fieldname.setValue(record.data.$realId);\r\n".
												  $blank_pre."                                        this.grid.$show_name_diff.setValue(record.data.$value);\r\n".
												  $blank_pre."                                        this.collapse();\r\n".
												  $blank_pre."                                     }\r\n".
												  $blank_pre."                                 }\r\n".
												  $blank_pre."                            },\r\n";

									if (Config_AutoCode::RELATION_VIEW_FULL){
										if (array_key_exists($fieldname,$relationSpecs))
										{
											$relationShow=$relationSpecs[$fieldname];
											foreach ($relationShow as $key_relation=>$value_relation) {
												$realId=DataObjectSpec::getRealIDColumnName($key_relation);
												$fieldInfo_relationshow=self::$fieldInfos[self::getTablename($key_relation)];
												$key_relation{0}=strtolower($key_relation{0});
												if (!contain(self::$relationStore,"{$key}StoreForCombo")){
													$showValue=$value;
													if ($value=="name") $showValue=strtolower($key_relation)."_".$value_relation;
													$relation_classcomment=self::relation_classcomment(self::$class_comments[$current_classname]);
													$relationStore_combo=",\r\n".
																	"    /**\r\n".
																	"     * {$relation_classcomment}\r\n".
																	"     */\r\n".
																	"    {$key}StoreForCombo:new Ext.data.Store({\r\n".
																	"        proxy: new Ext.data.HttpProxy({\r\n".
																	"            url: 'home/admin/src/httpdata/{$key_relation}.php'\r\n".
																	"        }),\r\n".
																	"        reader: new Ext.data.JsonReader({\r\n".
																	"            root: '{$key}s',\r\n".
																	"            autoLoad: true,\r\n".
																	"            totalProperty: 'totalCount',\r\n".
																	"            idProperty: '$realId'\r\n".
																	"        }, [\r\n".
																	"            {name: '$realId', mapping: '$realId'},\r\n";
													if (array_key_exists("level",$fieldInfo_relationshow)){
														$showLevel=strtolower($key)."_level";
														$relationStore_combo.="            {name: '$showLevel', mapping: 'level'},\r\n";
													}
													$relationStore_combo.="            {name: '$showValue', mapping: '$value'}\r\n".
																	"        ])\r\n".
																	"    })";
													$relationStore.=$relationStore_combo;
													self::$relationStore.=$relationStore_combo;
												}
											 }
										}
									}
								}
							}
							continue;
						}
					}
				}

				$column_type=self::column_type($field["Type"]);
				$isImage =self::columnIsImage($fieldname,$field["Comment"]);
				$isPassword=self::columnIsPassword($tablename,$fieldname);
				if ($fieldname==self::keyIDColumn($classname))
				{
					$fieldLabels.=$blank_pre."                            {xtype: 'hidden',name : '$fieldname',ref:'../$fieldname'";
				}else if ($isImage){
					$field_comment=$field["Comment"];
					$field_comment=self::columnCommentKey($field_comment,$fieldname);
					$result["isFileUpload"]="fileUpload: true,";
					$fieldLabels.=$blank_pre."                            {xtype: 'hidden',  name : '$fieldname',ref:'../$fieldname'},\r\n";
					$fieldLabels.=$blank_pre."                            {fieldLabel : '{$field_comment}',name : '{$fieldname}Upload',ref:'../{$fieldname}Upload',xtype:'fileuploadfield',\r\n".
								  $blank_pre."                              emptyText: '请上传{$field_comment}文件',buttonText: '',accept:'image/*',buttonCfg: {iconCls: 'upload-icon'}";
				}else if($isPassword){
					$field_comment=$field["Comment"];
					$field_comment=self::columnCommentKey($field_comment,$fieldname);
					$fieldLabels.=$blank_pre."                            {fieldLabel : '{$field_comment}(<font color=red>*</font>)',name : '{$fieldname}',inputType:'{$fieldname}',ref:'../{$fieldname}'},\r\n";
					$fieldLabels.=$blank_pre."                            {xtype: 'hidden',name : '{$fieldname}_old',ref:'../{$fieldname}_old'";

					$password_Add.=$blank_pre."            var {$fieldname}Obj=$appName_alias.$classname.View.Running.edit_window.{$fieldname};\r\n".
								   $blank_pre."            {$fieldname}Obj.allowBlank=false;\r\n".
								   $blank_pre."            if ({$fieldname}Obj.getEl()) {$fieldname}Obj.getEl().dom.parentNode.previousSibling.innerHTML =\"{$field_comment}(<font color=red>*</font>)\";\r\n";
					$password_update.="\r\n".
									  $blank_pre."            var {$fieldname}Obj=$appName_alias.$classname.View.Running.edit_window.{$fieldname};\r\n".
									  $blank_pre."            {$fieldname}Obj.allowBlank=true;\r\n".
									  $blank_pre."            if ({$fieldname}Obj.getEl()){$fieldname}Obj.getEl().dom.parentNode.previousSibling.innerHTML =\"{$field_comment}\";\r\n".
									  $blank_pre."            $appName_alias.$classname.View.Running.edit_window.{$fieldname}_old.setValue(this.getSelectionModel().getSelected().data.{$fieldname}.getValue());\r\n".
									  $blank_pre."            $appName_alias.$classname.View.Running.edit_window.{$fieldname}.setValue(\"\");\r\n";
				}else{
					$datatype=self::comment_type($field["Type"]);
					$field_comment=$field["Comment"];
					$field_comment=self::columnCommentKey($field_comment,$fieldname);
					if (!$field["IsPermitNull"])
					{
						$fr1="(<font color=red>*</font>)";
						$fr2=",allowBlank : false";
					}else{
						$fr1="";
						$fr2="";
					}
					//当使用form.getForm().submit()方式提交时，服务器得到的请求字段中的值总是combobox实际显示的值，也就是displayField:'text'的值;
					//将name属性修改为hiddenName，便会将value值提交给服务器
					if (($column_type=='enum')||($column_type=='bit')){
						$flName="hiddenName";
					}else{
						$flName="name";
					}
					$fieldLabels.=$blank_pre."                            {fieldLabel : '$field_comment$fr1',$flName : '$fieldname'$fr2";
					if (($datatype=='date')||contains($field_comment,array("日期","时间")))
					{
						$fieldLabels.=",xtype : 'datefield',format : \"Y-m-d\"";
					}elseif (($column_type=='int')||($datatype=='float')){
						$fieldLabels.=",xtype : 'numberfield'";
					}
					if ($column_type=='bit')
					{
						$fieldLabels.="\r\n".
								  $blank_pre."                                 ,xtype:'combo',ref:'../$fieldname',mode : 'local',triggerAction : 'all',\r\n".
								  $blank_pre."                                 lazyRender : true,editable: false,allowBlank : false,valueNotFoundText:'否',\r\n".
								  $blank_pre."                                 store : new Ext.data.SimpleStore({\r\n".
								  $blank_pre."                                     fields : ['value', 'text'],\r\n".
								  $blank_pre."                                     data : [['0', '否'], ['1', '是']]\r\n".
								  $blank_pre."                                 }),emptyText: '请选择$field_comment',\r\n".
								  $blank_pre."                                 valueField : 'value',displayField : 'text'\r\n".
								  $blank_pre."                            ";
					}
					if ($column_type=='enum')
					{
						$enum_columnDefine=self::enumDefines($field["Comment"]);
						$fieldLabels.=$blank_pre.",xtype:'combo',ref:'../$fieldname',\r\n".
									  $blank_pre."                                mode : 'local',triggerAction : 'all',lazyRender : true,editable: false,allowBlank : false,\r\n".
									  $blank_pre."                                store : new Ext.data.SimpleStore({\r\n".
									  $blank_pre."                                    fields : ['value', 'text'],\r\n".
									  $blank_pre."                                    data : [";
						$enumArr=array();
						foreach ($enum_columnDefine as $enum_column)
						{
							$enumArr[]="['".$enum_column["value"]."', '".$enum_column["comment"]."']";
						}
						$fieldLabels.=implode(",",$enumArr);
						$fieldLabels.="]\r\n".
									  $blank_pre."                                }),emptyText: '请选择$field_comment',\r\n".
									  $blank_pre."                                valueField : 'value',displayField : 'text'\r\n".
									  $blank_pre."                            ";
					}
					if (self::columnIsEmail($fieldname,$field_comment)){
						$fieldLabels.=",vtype:'email'";
					}
					if (self::columnIsTextArea($fieldname,$field["Type"]))
					{
						$fieldLabels.=",xtype : 'textarea',id:'$fieldname',ref:'$fieldname'";
					}
				}
				$fieldLabels.="},\r\n";
			}
		}
		$fieldLabels=substr($fieldLabels,0,strlen($fieldLabels)-3);
		$result["fieldLabels"]=$fieldLabels;

		$result["password_Add"]   =$password_Add;
		$result["password_update"]=$password_update;
		$result["treeLevelVisible_Add"]=$treeLevelVisible_Add;
		$result["treeLevelVisible_Update"]=$treeLevelVisible_Update;
		return $result;
	}

	/**
	 * 获取Ext "Textarea" 转换成在线编辑器
	 * @param string $appName_alias 应用别名
	 * @param string $classname 数据对象类名
	 * @param array $fieldInfo 表列信息列表
	 * @param string $blank_pre 空格字符串
	 */
	private static function model_textareaOnlineEditor($appName_alias,$classname,$instancename,$fieldInfo,$blank_pre="")
	{
		$result=array();
		$textareaOnlineditor_Replace="";
		$textareaOnlineditor_Add="";
		$textareaOnlineditor_Update="";
		$textareaOnlineditor_Save="";
		$textareaOnlineditor_Reset="";
		$textareaOnlineditor_Init="";
		$textareaOnlineditor_Init_func="";

		$textareaOnlineditor_Replace_array=array("UEditor"=>'',"ckEditor"=>'',"kindEditor"=>'',"xhEditor"=>'');
		$textareaOnlineditor_Add_array=array("UEditor"=>'',"ckEditor"=>'',"kindEditor"=>'',"xhEditor"=>'');
		$textareaOnlineditor_Update_array=array("UEditor"=>'',"ckEditor"=>'',"kindEditor"=>'',"xhEditor"=>'');
		$textareaOnlineditor_Save_array=array("UEditor"=>'',"ckEditor"=>'',"kindEditor"=>'',"xhEditor"=>'');
		$textareaOnlineditor_Reset_array=array("UEditor"=>'',"ckEditor"=>'',"kindEditor"=>'',"xhEditor"=>'');
		$reset_img="";
		$add_img="";
		$update_img="";
		$has_textarea=false;
		$isRedundancyCurrentHad=false;
		$redundancy_table_fields=self::$redundancy_table_fields[$classname];
		foreach ($fieldInfo as $fieldname=>$field)
		{
			if (self::isNotColumnKeywork($fieldname))
			{
				$column_type=self::column_type($field["Type"]);
				$isImage =self::columnIsImage($fieldname,$field["Comment"]);
				if ($fieldname==self::keyIDColumn($classname))
				{
					$result["tableFieldIdName"]=$fieldname;
				}else if ($isImage){
					if ($redundancy_table_fields){
						if (!$isRedundancyCurrentHad){
							$redundancy_fields=array();
							foreach ($redundancy_table_fields as $redundancy_table_field) {
								$redundancy_fields=array_merge($redundancy_fields,$redundancy_table_field);
							}
							$isRedundancyCurrentHad=true;
						}
						if (array_key_exists($fieldname, $redundancy_fields))continue;
					}
					$reset_img.="                        this.{$fieldname}Upload.setValue(this.{$fieldname}.getValue());\r\n";
					$add_img.="            $appName_alias.$classname.View.Running.edit_window.{$fieldname}Upload.setValue(\"\");\r\n";
					$update_img.="            $appName_alias.$classname.View.Running.edit_window.{$fieldname}Upload.setValue($appName_alias.$classname.View.Running.edit_window.{$fieldname}.getValue());\r\n";

				}else{
					$datatype=self::comment_type($field["Type"]);
					$field_comment=$field["Comment"];
					$field_comment=self::columnCommentKey($field_comment,$fieldname);

					if (self::columnIsTextArea($fieldname,$field["Type"]))
					{
						if ($redundancy_table_fields){
							if (!$isRedundancyCurrentHad){
								$redundancy_fields=array();
								foreach ($redundancy_table_fields as $redundancy_table_field) {
									$redundancy_fields=array_merge($redundancy_fields,$redundancy_table_field);
								}
								$isRedundancyCurrentHad=true;
							}
							if (array_key_exists($fieldname, $redundancy_fields))continue;
						}
						$has_textarea=true;
						$textareaOnlineditor_Replace_array["UEditor"].="                                this.editForm.$fieldname.setWidth(\"98%\");\r\n";
						$textareaOnlineditor_Replace_array["UEditor"].=$blank_pre."                                pageInit_ue_$fieldname();\r\n";
						$textareaOnlineditor_Replace_array["ckEditor"].="                                ckeditor_replace_$fieldname();\r\n";
						$textareaOnlineditor_Replace_array["kindEditor"].="                                $appName_alias.$classname.View.EditWindow.KindEditor_$fieldname = KindEditor.create('textarea[name=\"$fieldname\"]',{width:'98%',minHeith:'350px', filterMode:true});\r\n";
						$textareaOnlineditor_Replace_array["xhEditor"].="                                pageInit_$fieldname();\r\n";

						$textareaOnlineditor_Add_array["UEditor"].="                    if (ue_$fieldname)ue_$fieldname.setContent(\"\");\r\n";
						$textareaOnlineditor_Add_array["ckEditor"].="                    if (CKEDITOR.instances.$fieldname) CKEDITOR.instances.$fieldname.setData(\"\");\r\n";
						$textareaOnlineditor_Add_array["kindEditor"].="                    if ($appName_alias.$classname.View.EditWindow.KindEditor_$fieldname) $appName_alias.$classname.View.EditWindow.KindEditor_{$fieldname}.html(\"\");\r\n";

						$textareaOnlineditor_Update_array["UEditor"].="                    ue_$fieldname.ready(function(){ue_$fieldname.setContent(data.$fieldname);});\r\n";
						$textareaOnlineditor_Update_array["ckEditor"].="                    if (CKEDITOR.instances.$fieldname) CKEDITOR.instances.$fieldname.setData(data.$fieldname);\r\n";
						$textareaOnlineditor_Update_array["kindEditor"].="                    if ($appName_alias.$classname.View.EditWindow.KindEditor_$fieldname) $appName_alias.$classname.View.EditWindow.KindEditor_$fieldname.html(data.$fieldname);\r\n";
						$textareaOnlineditor_Update_array["xhEditor"].="                    if (xhEditor_$fieldname)xhEditor_$fieldname.setSource(data.$fieldname);\r\n";

						$textareaOnlineditor_Save_array["UEditor"].="                                if (ue_$fieldname)this.editForm.$fieldname.setValue(ue_$fieldname.getContent());\r\n";
						$textareaOnlineditor_Save_array["ckEditor"].="                                if (CKEDITOR.instances.$fieldname) this.editForm.$fieldname.setValue(CKEDITOR.instances.$fieldname.getData());\r\n";
						$textareaOnlineditor_Save_array["kindEditor"].="                                if ($appName_alias.$classname.View.EditWindow.KindEditor_$fieldname)this.editForm.$fieldname.setValue($appName_alias.$classname.View.EditWindow.KindEditor_$fieldname.html());\r\n";
						$textareaOnlineditor_Save_array["xhEditor"].="                                if (xhEditor_$fieldname)this.editForm.$fieldname.setValue(xhEditor_$fieldname.getSource());\r\n";

						$textareaOnlineditor_Reset_array["UEditor"].="                                if (ue_$fieldname) ue_$fieldname.setContent($appName_alias.$classname.View.Running.{$instancename}Grid.getSelectionModel().getSelected().data.$fieldname);\r\n";
						$textareaOnlineditor_Reset_array["ckEditor"].="                                if (CKEDITOR.instances.$fieldname) CKEDITOR.instances.$fieldname.setData($appName_alias.$classname.View.Running.{$instancename}Grid.getSelectionModel().getSelected().data.$fieldname);\r\n";
						$textareaOnlineditor_Reset_array["kindEditor"].="                                if ($appName_alias.$classname.View.EditWindow.KindEditor_$fieldname) $appName_alias.$classname.View.EditWindow.KindEditor_$fieldname.html($appName_alias.$classname.View.Running.{$instancename}Grid.getSelectionModel().getSelected().data.$fieldname);\r\n";
						$textareaOnlineditor_Reset_array["xhEditor"].="                                if (xhEditor_$fieldname) xhEditor_$fieldname.setSource($appName_alias.$classname.View.Running.{$instancename}Grid.getSelectionModel().getSelected().data.$fieldname);\r\n";
					}
				}
			}
		}
		if ($has_textarea){
			$textareaOnlineditor_Init=",\r\n".
									  "        /**\r\n".
									  "         * 在线编辑器类型。\r\n".
									  "         * 1:CkEditor,2:KindEditor,3:xhEditor,4:UEditor\r\n".
									  "         * 配合Action的变量配置\$online_editor\r\n".
									  "         */\r\n".
									  "        OnlineEditor:4";
			$textareaOnlineditor_Init_func="\r\n".
									  "        if (Ext.util.Cookies.get('OnlineEditor')!=null){\r\n".
									  "            $appName_alias.$classname.Config.OnlineEditor=parseInt(Ext.util.Cookies.get('OnlineEditor'));\r\n".
									  "        }\r\n";
			$textareaOnlineditor_Replace=",\r\n".
									  $blank_pre."                    afterrender:function(){\r\n".
									  $blank_pre."                        switch ($appName_alias.$classname.Config.OnlineEditor)\r\n".
									  $blank_pre."                        {\r\n".
									  $blank_pre."                            case 1:\r\n".
									  $blank_pre.$textareaOnlineditor_Replace_array["ckEditor"].
									  $blank_pre."                                break\r\n".
									  $blank_pre."                            case 2:\r\n".
									  $blank_pre.$textareaOnlineditor_Replace_array["kindEditor"].
									  $blank_pre."                                break\r\n".
									  $blank_pre."                            case 3:\r\n".
									  $blank_pre.$textareaOnlineditor_Replace_array["xhEditor"].
									  $blank_pre."                                break\r\n".
									  $blank_pre."                            default:\r\n".
									  $blank_pre.$textareaOnlineditor_Replace_array["UEditor"].
									  $blank_pre."                        }\r\n".
									  $blank_pre."                    }";
			$textareaOnlineditor_Add=$add_img.
									  $blank_pre."            switch ($appName_alias.$classname.Config.OnlineEditor)\r\n".
									  $blank_pre."            {\r\n".
									  $blank_pre."                case 1:\r\n".
									  $blank_pre.$textareaOnlineditor_Add_array["ckEditor"].
									  $blank_pre."                    break\r\n".
									  $blank_pre."                case 2:\r\n".
									  $blank_pre.$textareaOnlineditor_Add_array["kindEditor"].
									  $blank_pre."                    break\r\n".
									  $blank_pre."                case 3:\r\n".
									  $blank_pre."                    break\r\n".
									  $blank_pre."                default:\r\n".
									  $blank_pre.$textareaOnlineditor_Add_array["UEditor"].
									  $blank_pre."            }\r\n";
			$textareaOnlineditor_Update=$update_img.
									  $blank_pre."            var data = this.getSelectionModel().getSelected().data;\r\n".
									  $blank_pre."            switch ($appName_alias.$classname.Config.OnlineEditor)\r\n".
									  $blank_pre."            {\r\n".
									  $blank_pre."                case 1:\r\n".
									  $blank_pre.$textareaOnlineditor_Update_array["ckEditor"].
									  $blank_pre."                    break\r\n".
									  $blank_pre."                case 2:\r\n".
									  $blank_pre.$textareaOnlineditor_Update_array["kindEditor"].
									  $blank_pre."                    break\r\n".
									  $blank_pre."                case 3:\r\n".
									  $blank_pre.$textareaOnlineditor_Update_array["xhEditor"].
									  $blank_pre."                    break\r\n".
									  $blank_pre."                default:\r\n".
									  $blank_pre.$textareaOnlineditor_Update_array["UEditor"].
									  $blank_pre."            }\r\n";
			$textareaOnlineditor_Save=$blank_pre."                        switch ($appName_alias.$classname.Config.OnlineEditor)\r\n".
									  $blank_pre."                        {\r\n".
									  $blank_pre."                            case 1:\r\n".
									  $blank_pre.$textareaOnlineditor_Save_array["ckEditor"].
									  $blank_pre."                                break\r\n".
									  $blank_pre."                            case 2:\r\n".
									  $blank_pre.$textareaOnlineditor_Save_array["kindEditor"].
									  $blank_pre."                                break\r\n".
									  $blank_pre."                            case 3:\r\n".
									  $blank_pre.$textareaOnlineditor_Save_array["xhEditor"].
									  $blank_pre."                                break\r\n".
									  $blank_pre."                            default:\r\n".
									  $blank_pre.$textareaOnlineditor_Save_array["UEditor"].
									  $blank_pre."                        }\r\n";
			$textareaOnlineditor_Reset=$reset_img.
									  $blank_pre."                        switch ($appName_alias.$classname.Config.OnlineEditor)\r\n".
									  $blank_pre."                        {\r\n".
									  $blank_pre."                            case 1:\r\n".
									  $blank_pre.$textareaOnlineditor_Reset_array["ckEditor"].
									  $blank_pre."                                break\r\n".
									  $blank_pre."                            case 2:\r\n".
									  $blank_pre.$textareaOnlineditor_Reset_array["kindEditor"].
									  $blank_pre."                                break\r\n".
									  $blank_pre."                            case 3:\r\n".
									  $blank_pre.$textareaOnlineditor_Reset_array["xhEditor"].
									  $blank_pre."                                break\r\n".
									  $blank_pre."                            default:\r\n".
									  $blank_pre.$textareaOnlineditor_Reset_array["UEditor"].
									  $blank_pre."                        }\r\n";
		}else{
			$textareaOnlineditor_Add=$add_img;
			$textareaOnlineditor_Update=$update_img;
			$textareaOnlineditor_Reset=$reset_img;
		}
		$result["textareaOnlineditor_Replace"]=$textareaOnlineditor_Replace;
		$result["textareaOnlineditor_Add"]=$textareaOnlineditor_Add;
		$result["textareaOnlineditor_Update"]=$textareaOnlineditor_Update;
		$result["textareaOnlineditor_Save"]=$textareaOnlineditor_Save;
		$result["textareaOnlineditor_Reset"]=$textareaOnlineditor_Reset;
		$result["textareaOnlineditor_Init"]=$textareaOnlineditor_Init;
		$result["textareaOnlineditor_Init_func"]=$textareaOnlineditor_Init_func;
		return $result;
	}

	/**
	 * Ext "Tabs" 中"onAddItems"包含的viewdoblock
	 * @param string $classname 数据对象类名
	 * @param array $fieldInfo 表列信息列表
	 */
	private static function model_viewblock($tablename,$classname,$fieldInfo)
	{
		$viewdoblock="";//Ext "Tabs" 中"onAddItems"包含的viewdoblock
		$isTreelevelViewInfoHad=false;
		foreach ($fieldInfo as $fieldname=>$field)
		{

			if (is_array(self::$relation_viewfield)&&(count(self::$relation_viewfield)>0))
			{
				if (array_key_exists($classname,self::$relation_viewfield)){
					$relationSpecs=self::$relation_viewfield[$classname];
					if (array_key_exists($fieldname,$relationSpecs)){
						$relationShow=$relationSpecs[$fieldname];
						foreach ($relationShow as $key=>$value) {
							if ((!array_key_exists($value,$fieldInfo))||($classname==$key)||($value=='name')){
								$field_comment=$field["Comment"];
								$field_comment=self::columnCommentKey($field_comment,$fieldname);
								foreach ($relationShow as $key=>$value) {
									$realId=DataObjectSpec::getRealIDColumnName($key);
									$show_fieldname=$value;
									if ($realId!=$fieldname){
										if (contain($fieldname,"_id")){
											$fieldname=str_replace("_id","",$fieldname);
										}
										$show_fieldname.="_".$fieldname;
									}
									if ($show_fieldname=="name"){
										$show_fieldname= strtolower($key)."_".$value;
									}
									$fieldInfo_relationshow=self::$fieldInfos[self::getTablename($key)];
									$show_TreelevelViewInfo="";
									if (!$isTreelevelViewInfoHad){
										if (array_key_exists("parent_id",$fieldInfo_relationshow)){
											$key{0}=strtolower($key{0});
											$show_TreelevelViewInfo="<tpl if=\"$show_fieldname\">({{$key}ShowAll})</tpl>";
											$isTreelevelViewInfoHad=true;
										}
									}
									if (!array_key_exists("$show_fieldname",$fieldInfo)){
										$viewdoblock.="                         '    <tr class=\"entry\"><td class=\"head\">$field_comment</td><td class=\"content\">{{$show_fieldname}}$show_TreelevelViewInfo</td></tr>',\r\n";
									}
								}
							}
						}
						continue;
					}
				}
			}
			if (self::isNotColumnKeywork($fieldname))
			{
				if ($fieldname==self::keyIDColumn($classname))
				{
					continue;
				}
				$field_comment=$field["Comment"];
				$field_comment=self::columnCommentKey($field_comment,$fieldname);
				$datatype =self::comment_type($field["Type"]);
				$dateformat="";
				if (($datatype=='date')||contains($field_comment,array("日期","时间")))
				{
					$dateformat=":date(\"Y-m-d\")";
				}
				$isImage =self::columnIsImage($fieldname,$field["Comment"]);
				$column_type=self::column_type($field["Type"]);
				$isPassword=self::columnIsPassword($tablename,$fieldname);
				$isTextarea=self::columnIsTextArea($fieldname,$field["Type"]);
				if ($isImage){
					if (contain($field_comment,"路径"))$field_comment=str_replace("路径", "", $field_comment);
					$viewdoblock.="                         '    <tr class=\"entry\"><td class=\"head\">{$field_comment}路径</td><td class=\"content\">{{$fieldname}}</td></tr>',\r\n";
					$viewdoblock.="                         '    <tr class=\"entry\"><td class=\"head\">$field_comment</td><td class=\"content\"><tpl if=\"{$fieldname}\"><a href=\"upload/images/{{$fieldname}}\" target=\"_blank\"><img src=\"upload/images/{{$fieldname}}\" /></a></tpl></td></tr>',\r\n";
				}else if ($column_type=='bit'){
					$viewdoblock.="                         '    <tr class=\"entry\"><td class=\"head\">$field_comment</td><td class=\"content\"><tpl if=\"{$fieldname} == true\">是</tpl><tpl if=\"{$fieldname} == false\">否</tpl></td></tr>',\r\n";
				}else if ($datatype=='enum'){
					$viewdoblock.="                         '    <tr class=\"entry\"><td class=\"head\">$field_comment</td><td class=\"content\">{{$fieldname}Show}</td></tr>',\r\n";
				}else if ($isPassword){
					$viewdoblock.="";
				}else if ($isTextarea){
					$viewdoblock.="                         '    <tr class=\"entry\"><td class=\"head\">$field_comment</td><td class=\"content\">{{$fieldname}Show}</td></tr>',\r\n";
				}else{
					$viewdoblock.="                         '    <tr class=\"entry\"><td class=\"head\">$field_comment</td><td class=\"content\">{{$fieldname}{$dateformat}}</td></tr>',\r\n";
				}
			}
		}
		$viewdoblock=substr($viewdoblock,0,strlen($viewdoblock)-2);
		return $viewdoblock;
	}

	/**
	 * 获取Ext "Grid" 中包含的columns
	 * @param string $tablename 表名称
	 * @param string $classname 数据对象类名
	 * @param array $fieldInfo 表列信息列表
	 * @param string $blank_pre 空格字符串
	 */
	private static function model_columns($tablename,$classname,$fieldInfo,$blank_pre="")
	{
		$columns="";//Ext "Grid" 中包含的columns
		foreach ($fieldInfo as $fieldname=>$field)
		{
			if (is_array(self::$relation_viewfield)&&(count(self::$relation_viewfield)>0))
			{
				if (array_key_exists($classname,self::$relation_viewfield)){
					$relationSpecs=self::$relation_viewfield[$classname];
					if (array_key_exists($fieldname,$relationSpecs)){
						$relationShow=$relationSpecs[$fieldname];
						foreach ($relationShow as $key=>$value) {
							if ((!array_key_exists($value,$fieldInfo))||($classname==$key)){
								$field_comment=$field["Comment"];
								$field_comment=self::columnCommentKey($field_comment,$fieldname);
								foreach ($relationShow as $key=>$value) {
									$realId=DataObjectSpec::getRealIDColumnName($key);
									$show_fieldname=$value;
									if ($realId!=$fieldname){
										if (contain($fieldname,"_id")){
											$fieldname=str_replace("_id","",$fieldname);
										}
										$show_fieldname.="_".$fieldname;
									}
									if ($show_fieldname=="name"){
										$show_fieldname=strtolower($key)."_".$show_fieldname;
									}
									if (!array_key_exists("$show_fieldname",$fieldInfo)){
										$columns.=$blank_pre."                        {header : '$field_comment',dataIndex : '{$show_fieldname}'},\r\n";
									}
								}
							}else{
								if ($value=="name"){
									$field_comment=$field["Comment"];
									$field_comment=self::columnCommentKey($field_comment,$fieldname);
									$show_fieldname= strtolower($key)."_".$value;
									if (!array_key_exists("$show_fieldname",$fieldInfo)){
										$columns.=$blank_pre."                        {header : '$field_comment',dataIndex : '{$show_fieldname}'},\r\n";
									}
								}
							}
						}
						continue;
					}
				}
			}
			if (self::isNotColumnKeywork($fieldname))
			{
				if ($fieldname==self::keyIDColumn($classname))
				{
					$columns.=$blank_pre."                        {header : '标识',dataIndex : '{$fieldname}',hidden:true},\r\n";
					continue;
				}
				if (self::columnIsImage($fieldname,$field["Comment"]))continue;
				if (self::columnIsTextArea($fieldname,$field["Type"]))continue;

				$isPassword=self::columnIsPassword($tablename,$fieldname);
				if ($isPassword)continue;
				$field_comment=$field["Comment"];
				$field_comment=self::columnCommentKey($field_comment,$fieldname);
				$datatype=self::comment_type($field["Type"]);
				if ($datatype=='enum'){
					$columns.=$blank_pre."                        {header : '{$field_comment}',dataIndex : '{$fieldname}Show'";
				}else{
					$columns.=$blank_pre."                        {header : '$field_comment',dataIndex : '{$fieldname}'";
				}
				if (($datatype=='date')||contains($field_comment,array("日期","时间")))
				{
					$columns.=",renderer:Ext.util.Format.dateRenderer('Y-m-d')";
				}

				$column_type=self::column_type($field["Type"]);
				if ($column_type=='bit'){
					$columns.=",renderer:function(value){if (value == true) {return \"是\";}else{return \"否\";}}";
				}
				$columns.="},\r\n";
			}
		}
		$columns=substr($columns,0,strlen($columns)-3);
		return $columns;
	}

	/**
	 * 获取Ext "Grid" 中"tbar"包含的items中的items<br/>
	 * 获取重置语句<br/>
	 * 获取查询中的语句<br/>
	 * @param string $appName_alias 应用别名
	 * @param string $classname 数据对象类名
	 * @param string $instance_name 实体变量
	 * @param array $fieldInfo 表列信息列表
	 * @param string $blank_pre 空格字符串
	 */
	private static function model_filters($appName_alias,$classname,$instancename,$fieldInfo,$blank_pre="")
	{
		$filterFields             ="";//Ext "Grid" 中"tbar"包含的items中的items
		$filterReset              ="";//重置语句
		$filterdoSelect           ="";//查询中的语句
		$filterwordNames          =array();
		$filterfilter			  ="";
		if (array_key_exists($classname, self::$filter_fieldnames))
		{
			$filterwords=self::$filter_fieldnames[$classname];
			$instancename_pre=$instancename{0};
			$filterfilter=$blank_pre."                this.filter       ={";
			foreach ($fieldInfo as $fieldname=>$field)
			{
				$field_comment=$field["Comment"];
				$field_comment=self::columnCommentKey($field_comment,$fieldname);
				if (in_array($fieldname, $filterwords))
				{
					$fname=$instancename_pre.$fieldname;
					$datatype=self::comment_type($field["Type"]);
					$filterFields.=$blank_pre."                                '{$field_comment}','&nbsp;&nbsp;',";
					if (($datatype=='date')||contains($field_comment,array("日期","时间")))
					{
						$filterFields.=$blank_pre."{xtype : 'datefield',ref: '../$fname',format : \"Y-m-d\"";
					}else{
						$filterFields.="{ref: '../$fname'";
					}
					$filterwordNames[]=$fname;
					$column_type=self::column_type($field["Type"]);
					if ($column_type=='bit')
					{
						$filterFields.=",xtype:'combo',mode : 'local',\r\n".
								$blank_pre."                                    triggerAction : 'all',lazyRender : true,editable: false,\r\n".
								$blank_pre."                                    store : new Ext.data.SimpleStore({\r\n".
								$blank_pre."                                        fields : ['value', 'text'],\r\n".
								$blank_pre."                                        data : [['0', '否'], ['1', '是']]\r\n".
								$blank_pre."                                    }),\r\n".
								$blank_pre."                                    valueField : 'value',displayField : 'text'\r\n".
								$blank_pre."                                ";
					}
					if ($column_type=='enum')
					{
						$enum_columnDefine=self::enumDefines($field["Comment"]);
						$filterFields.=",xtype:'combo',mode : 'local',\r\n".
								$blank_pre."                                    triggerAction : 'all',lazyRender : true,editable: false,\r\n".
								$blank_pre."                                    store : new Ext.data.SimpleStore({\r\n".
								$blank_pre."                                        fields : ['value', 'text'],\r\n".
								$blank_pre."                                        data : [";
						$enumArr=array();
						foreach ($enum_columnDefine as $enum_column)
						{
							$enumArr[]="['".$enum_column["value"]."', '".$enum_column["comment"]."']";
						}
						$filterFields.=implode(",",$enumArr);
						$filterFields.="]\r\n".
								$blank_pre."                                    }),\r\n".
								$blank_pre."                                    valueField : 'value',displayField : 'text'\r\n".
								$blank_pre."                                ";
					}

					if ($filterwords["relation_show"]){
						if (array_key_exists($fieldname, $filterwords["relation_show"])){
							$con_relation_class=$filterwords["relation_show"][$fieldname]["relation_class"];
							$show_name         =$filterwords["relation_show"][$fieldname]["show_name"];
							$store_con_relation_class=$con_relation_class;
							$store_con_relation_class[0]=strtolower($store_con_relation_class[0]);
							$storeName="$appName_alias.$classname.Store.".$store_con_relation_class."StoreForCombo";
							$fieldInfo_relationshow=self::$fieldInfos[self::getTablename($con_relation_class)];
							if (array_key_exists("parent_id",$fieldInfo_relationshow)){
								$fieldname=self::getShowFieldNameByClassname($con_relation_class);
								$fsname=$instancename_pre.$fieldname;
								$con_relation_class{0}=strtolower($con_relation_class{0});
								$filterFields.=", xtype:'hidden'},{\r\n".
											   $blank_pre."                                      xtype:'combotree',ref:'../{$fsname}',grid:this,\r\n".
											   $blank_pre."                                      emptyText: '请选择{$field_comment}',canFolderSelect:true,flex:1,editable:false,\r\n".
											   $blank_pre."                                      tree: new Ext.tree.TreePanel({\r\n".
											   $blank_pre."                                          dataUrl: 'home/admin/src/httpdata/{$con_relation_class}Tree.php',\r\n".
											   $blank_pre."                                          root: {nodeType: 'async'},border: false,rootVisible: false,\r\n".
											   $blank_pre."                                          listeners: {\r\n".
											   $blank_pre."                                              beforeload: function(n) {if (n) {this.getLoader().baseParams.id = n.attributes.id;}}\r\n".
											   $blank_pre."                                          }\r\n".
											   $blank_pre."                                      }),\r\n".
											   $blank_pre."                                      onSelect: function(cmb, node) {\r\n".
											   $blank_pre."                                          this.grid.topToolbar.{$fname}.setValue(node.attributes.id);\r\n".
											   $blank_pre."                                          this.setValue(node.attributes.text);\r\n".
											   $blank_pre."                                      }\r\n".
											   $blank_pre."                                ";
							}else{
								$filterFields.=",xtype: 'combo',\r\n".
											  $blank_pre."                                     store:{$storeName},hiddenName : '{$fieldname}',\r\n".
											  $blank_pre."                                     emptyText: '请选择{$field_comment}',itemSelector: 'div.search-item',\r\n".
											  $blank_pre."                                     loadingText: '查询中...',width:280,pageSize:$appName_alias.$classname.Config.PageSize,\r\n".
											  $blank_pre."                                     displayField:'{$show_name}',valueField:'{$fieldname}',\r\n".
											  $blank_pre."                                     mode: 'remote',editable:true,minChars: 1,autoSelect :true,typeAhead: false,\r\n".
											  $blank_pre."                                     forceSelection: true,triggerAction: 'all',resizable:true,selectOnFocus:true,\r\n".
											  $blank_pre."                                     tpl:new Ext.XTemplate(\r\n".
											  $blank_pre."                                         '<tpl for=\".\"><div class=\"search-item\">',\r\n".
											  $blank_pre."                                         '<h3>{{$show_name}}</h3>',\r\n".
											  $blank_pre."                                         '</div></tpl>'\r\n".
											  $blank_pre."                                     )\r\n".
											  $blank_pre."                                ";

							}
						}
					}

					$filterFields.="},'&nbsp;&nbsp;',\r\n";
					$filterReset.=$blank_pre."                                        this.topToolbar.$fname.setValue(\"\");\r\n";
					$filterdoSelect.=$blank_pre."                var $fname = this.topToolbar.$fname.getValue();\r\n";
					$filterfilter.="'$fieldname':$fname,";
				}
			}
			if (strlen($filterFields)>0)
			{
				$filterFields=substr($filterFields,0,strlen($filterFields)-2);
				$filterReset=substr($filterReset,0,strlen($filterReset)-2);
				$filterdoSelect=substr($filterdoSelect,0,strlen($filterdoSelect)-2);
				$filterfilter=substr($filterfilter,0,strlen($filterfilter)-1);
				$filterfilter=$filterfilter."};";
			}
		}
		$result["filterwordNames"]=$filterwordNames;
		$result["filterFields"]   =$filterFields;
		$result["filterReset"]    =$filterReset;
		if (endWith($filterfilter,"{"))$filterfilter="";
		$result["filterdoSelect"] =$filterdoSelect."\r\n".$filterfilter;
		return $result;
	}

	/**
	 * 批量上传图片
	 * @param string $appName_alias 应用别名
	 * @param string $classname 数据对象类名
	 * @param string $instance_name 实体变量
	 * @param array $fieldInfo 表列信息列表
	 */
	private static function model_upload($appName_alias,$classname,$instancename,$fieldInfo)
	{
		$menu_uploadImg=",\r\n";
		$batchUploadImagesWinow=",\r\n";
		$isImage_once=false;
		$uploadServiceUrl=",\r\n";

		$moreImageUploads="if ($appName_alias.$classname.View.Running.batchUploadImagesWindow==null){\r\n".
						  "                $appName_alias.$classname.View.Running.batchUploadImagesWindow=new $appName_alias.$classname.View.BatchUploadImagesWindow();\r\n".
						  "            }\r\n";
		$isRedundancyCurrentHad=false;
		$redundancy_table_fields=self::$redundancy_table_fields[$classname];
		foreach ($fieldInfo as $fieldname=>$field)
		{
			if (!$isRedundancyCurrentHad){
				$redundancy_fields=array();
				if (is_array($redundancy_table_fields)&&(count($redundancy_table_fields)>0))
				{
					foreach ($redundancy_table_fields as $redundancy_table_field) {
						$redundancy_fields=array_merge($redundancy_fields,$redundancy_table_field);
					}
				}
				$isRedundancyCurrentHad=true;
			}
			if (array_key_exists($fieldname, $redundancy_fields))continue;
			$isImage =self::columnIsImage($fieldname,$field["Comment"]);
			$field_comment=$field["Comment"];
			$field_comment=self::columnCommentKey($field_comment,$fieldname);
			$field_comment=str_replace('路径',"",$field_comment);
			if ($isImage){
				$menu_uploadImg.="                                            {text:'批量导入{$field_comment}',iconCls : 'icon-import',scope:this,handler:function(){this.batchUploadImages(\"upload_{$fieldname}_files\",\"$field_comment\")}},\r\n";
				$fieldname_funcname=$fieldname;
				$fieldname_funcname{0}=strtoupper($fieldname_funcname);
				if ($isImage_once){
					$uploadServiceUrl.="                            if (this.uploadForm.upload_file.name==\"upload_{$fieldname}_files\"){\r\n".
									   "                                uploadImageUrl='index.php?go=admin.upload.upload{$classname}{$fieldname_funcname}s';\r\n".
									   "                            }\r\n";
					$moreImageUploads="if ($appName_alias.$classname.View.Running.batchUploadImagesWindow!=null){\r\n".
									  "                $appName_alias.$classname.View.Running.batchUploadImagesWindow.destroy();\r\n".
									  "                $appName_alias.$classname.View.Running.batchUploadImagesWindow=null;\r\n".
									  "            }\r\n".
									  "            $appName_alias.$classname.View.Running.batchUploadImagesWindow=new $appName_alias.$classname.View.BatchUploadImagesWindow();\r\n";
				}else{
					$uploadServiceUrl="var uploadImageUrl='index.php?go=admin.upload.upload{$classname}{$fieldname_funcname}s';\r\n";
				}
				$isImage_once=true;
			}
		}
		if ($isImage_once){
			$batchUploadImagesWinow.=<<<BATCHUPLOADIMAGESWINDOW
	/**
	 * 窗口：批量上传商品图片
	 */
	BatchUploadImagesWindow:Ext.extend(Ext.Window,{
		constructor : function(config) {
			config = Ext.apply({
				width : 400,height : 180,minWidth : 300,minHeight : 100,closeAction : "hide",
				layout : 'fit',plain : true,bodyStYle : 'padding:5px;',buttonAlign : 'center',
				items : [
					new Ext.form.FormPanel({
						ref:'uploadForm',fileUpload: true,
						width: 500,labelWidth: 50,autoHeight: true,baseCls: 'x-plain',
						frame:true,bodyStyle: 'padding: 10px 10px 10px 10px;',
						defaults: {
							anchor: '95%',allowBlank: false,msgTarget: 'side'
						},
						items : [{
							xtype : 'fileuploadfield',fieldLabel : '文 件',ref:'upload_file',
							emptyText: '请批量上传{$field_comment}文件(zip)',buttonText: '',
							accept:'application/zip,application/x-zip-compressed',
							buttonCfg: {iconCls: 'upload-icon'}
						},
						{xtype : 'displayfield',value:'*.图片名不能重名<br/>*.压缩文件最大不要超过50M'}]
					})
				],
				buttons : [{
						text : '上 传',
						scope:this,
						handler : function() {
							uploadImagesWindow     =this;
							validationExpression   =/([\u4E00-\u9FA5]|\w)+(.zip|.ZIP)$/;
							var isValidExcelFormat = new RegExp(validationExpression);
							var result             = isValidExcelFormat.test(this.uploadForm.upload_file.getValue());
							if (!result){
								Ext.Msg.alert('提示', '请上传ZIP文件，后缀名为zip！');
								return;
							}
							$uploadServiceUrl
							if (this.uploadForm.getForm().isValid()) {
								Ext.Msg.show({
									title : '请等待',msg : '文件正在上传中，请稍后...',
									animEl : 'loading',icon : Ext.Msg.WARNING,
									closable : true,progress : true,progressText : '',width : 300
								});
								this.uploadForm.getForm().submit({
									url : uploadImageUrl,
									success : function(form, response) {
										Ext.Msg.alert('成功', '上传成功');
										uploadImagesWindow.hide();
										uploadImagesWindow.uploadForm.upload_file.setValue('');
										$appName_alias.$classname.View.Running.{$instancename}Grid.doSelect{$classname}();
									},
									failure : function(form, response) {
										if (response.result&&response.result.data){
											Ext.Msg.alert('错误', response.result.data);
										}
									}
								});
							}
						}
					},{
						text : '取 消',
						scope:this,
						handler : function() {
							this.uploadForm.upload_file.setValue('');
							this.hide();
						}
					}]
				}, config);
			$appName_alias.$classname.View.BatchUploadImagesWindow.superclass.constructor.call(this, config);
		}
	}),

BATCHUPLOADIMAGESWINDOW;

			$openBatchUploadImagesWindow=",\r\n";
			$openBatchUploadImagesWindow.=<<<BATCHUPLOADIMAGES
		/**
		 * 批量上传商品图片
		 */
		batchUploadImages:function(inputname,title){
			$moreImageUploads
			$appName_alias.$classname.View.Running.batchUploadImagesWindow.setTitle("批量上传"+title);
			$appName_alias.$classname.View.Running.batchUploadImagesWindow.uploadForm.upload_file.name=inputname;
			$appName_alias.$classname.View.Running.batchUploadImagesWindow.show();
		}

BATCHUPLOADIMAGES;
		}
		$menu_uploadImg=substr($menu_uploadImg,0,strlen($menu_uploadImg)-3);
		$openBatchUploadImagesWindow=isset($openBatchUploadImagesWindow) ? $openBatchUploadImagesWindow=substr($openBatchUploadImagesWindow,0,strlen($openBatchUploadImagesWindow)-1) : "";
		$batchUploadImagesWinow=substr($batchUploadImagesWinow,0,strlen($batchUploadImagesWinow)-1);
		$result["menu_uploadImg"]   =$menu_uploadImg;
		$result["openBatchUploadImagesWindow"]   =$openBatchUploadImagesWindow;
		$result["batchUploadImagesWinow"]   =$batchUploadImagesWinow;
		return $result;
	}

	/**
	 * 表注释只获取第一行内容
	 * @param array $classcomment 表注释
	 */
	private static function relation_classcomment($classcomment)
	{
		$classcomment=str_replace("关系表","",$classcomment);
		if (contain($classcomment,"\r")||contain($classcomment,"\n")){
			$classcomment=preg_split("/[\s,]+/", $classcomment);
			$classcomment=$classcomment[0];
		}
		return $classcomment;
	}

	/**
	 * 将表列定义转换成使用ExtJs生成的表示层tpl文件定义的内容
	 * @param string $tablename 表名称
	 * @param array $fieldInfo 表列信息列表
	 */
	private static function tableToViewTplDefine($tablename,$fieldInfo)
	{
		$result ="{extends file=\"\$templateDir/layout/normal/layout.tpl\"}\r\n".
				 "{block name=body}\r\n".
				 "    <div id=\"loading-mask\"></div>\r\n".
				 "    <div id=\"loading\">\r\n".
				 "        <div class=\"loading-indicator\"><img src=\"{\$url_base}common/js/ajax/ext/resources/images/extanim32.gif\" width=\"32\" height=\"32\" style=\"margin-right:8px;\" align=\"absmiddle\"/>正在加载中...</div>\r\n".
				 "    </div>\r\n";
		foreach ($fieldInfo as $fieldname=>$field)
		{
			if (self::columnIsTextArea($fieldname,$field["Type"]))
			{
				$result.="    {\$editorHtml}\r\n";
				break;
			}

			if (Config_AutoCode::RELATION_VIEW_FULL)
			{
				$classname=self::getClassname($tablename);

				if (is_array(self::$relation_all)&&(count(self::$relation_all)>0))
				{
					if (array_key_exists($classname,self::$relation_all))
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
										$result.="    {\$editorHtml}\r\n";
										break 3;
									}
								}
							}
						}
					}
				}
			}
		}
		$result .="{/block}\r\n";
		return $result;
	}

	/**
	 * 保存生成的Js代码到指定命名规范的文件中
	 * @param string $tablename 表名称
	 * @param string $defineJsFileContent 生成的代码
	 */
	private static function saveJsDefineToDir($tablename,$defineJsFileContent)
	{
		$filename =self::getInstancename($tablename).Config_F::SUFFIX_FILE_JS;
		if (Config_AutoCode::JSFILE_DIRECT_CORE){
			$dir      =self::$view_js_package.Config_F::VIEW_CORE.DIRECTORY_SEPARATOR;
		}else{
			$dir      =self::$view_js_package.self::getInstancename($tablename).DIRECTORY_SEPARATOR;
		}
		return self::saveDefineToDir($dir,$filename,$defineJsFileContent);
	}

	/**
	 * 保存生成的Ajax服务代码到指定命名规范的文件中
	 * @param string $classname 类名称
	 * @param string $defineAjaxPhpFileContent 生成的代码
	 */
	private static function saveoAjaxPhpDefineToDir($filename,$defineAjaxPhpFileContent)
	{
		$dir      =self::$ajax_dir_full;
		return self::saveDefineToDir($dir,$filename,$defineAjaxPhpFileContent);
	}

	/**
	 * 保存生成的tpl代码到指定命名规范的文件中
	 * @param string $tablename 表名称
	 * @param string $defineTplFileContent 生成的代码
	 */
	private static function saveTplDefineToDir($tablename,$defineTplFileContent)
	{
		$filename =self::getInstancename($tablename).Config_F::SUFFIX_FILE_TPL;
		$dir      =self::$view_core.Gc::$appName.DIRECTORY_SEPARATOR;
		return self::saveDefineToDir($dir,$filename,$defineTplFileContent);
	}
}

?>