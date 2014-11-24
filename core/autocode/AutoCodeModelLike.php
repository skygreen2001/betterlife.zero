<?php
/**
 +---------------------------------<br/>
 * 工具类:自动生成代码-生成单张表或者对应类的前后台所有模板文件<br/>
 +---------------------------------<br/>
 * @category betterlife
 * @package core.autocode
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCodeModelLike extends AutoCode
{
	/**
	 * 自动生成代码-一键生成前后台所有模板文件
	 * @param array|string $table_names
	 * 示例如下：
	 *  1.array:array('bb_user_admin','bb_core_blog')
	 *  2.字符串:'bb_user_admin,bb_core_blog'
	 */
	public static function AutoCode($table_names="")
	{
		$dest_directory=Gc::$nav_root_path."tools".DS."tools".DS."autocode".DS;
		$filename=$dest_directory."autocode.config.xml";
		AutoCodeValidate::run($table_names);
		if(Config_AutoCode::ALWAYS_AUTOCODE_XML_NEW)AutoCodeConfig::run();
		if (!file_exists($filename)){
			AutoCodeConfig::run();
			die("&nbsp;&nbsp;自动生成代码的配置文件已生成，请再次运行以生成所有web应用代码！");
		}
		self::$showReport.=AutoCodeFoldHelper::foldEffectReady();
		//生成实体数据对象类
		AutoCodeDomain::$save_dir =self::$save_dir;
		AutoCodeDomain::$type     =2;
		self::$showReport.=AutoCodeFoldHelper::foldbeforedomain();
		AutoCodeDomain::AutoCode($table_names);
		self::$showReport.=AutoCodeFoldHelper::foldafterdomain();
		AutoCode::$isOutputCss=false;

		//生成提供服务类[前端和后端通用模板]
		AutoCodeService::$save_dir =self::$save_dir;
		self::$showReport.=AutoCodeFoldHelper::foldbeforeservice();
		AutoCodeService::$type     =2;
		AutoCodeService::AutoCode($table_names);
		self::$showReport.=AutoCodeFoldHelper::foldafterservice();

		//生成Action类[前端和后端]
		AutoCodeAction::$save_dir =self::$save_dir;
		self::$showReport.=AutoCodeFoldHelper::foldbeforeaction();
		AutoCodeAction::$type     =0;
		AutoCodeAction::AutoCode($table_names);
		AutoCodeAction::$type     =1;
		AutoCodeAction::AutoCode($table_names);
		self::$showReport.=AutoCodeFoldHelper::foldafteraction();

		//生成前端表示层
		self::$showReport.=AutoCodeFoldHelper::foldbeforeviewdefault();
		AutoCodeViewDefault::$save_dir =self::$save_dir;
		AutoCodeViewDefault::$type     =0;
		AutoCodeViewDefault::AutoCode($table_names);
		AutoCodeViewDefault::$type     =1;
		AutoCodeViewDefault::AutoCode($table_names);
		self::$showReport.=AutoCodeFoldHelper::foldafterviewdefault();
		self::$showReport.= "</div>";

		//将新添加的内容放置在文件最后作为可覆盖的内容
		AutoCodePreviewReportLike::init();

		//前台
		self::createManageService($table_names);

		//模板
		self::createModelIndex($table_names);

		//将AutoCodePreviewReport相关值转移到AutoCodePreviewReportLike里【AutoCode具体生成代码的报告数组都是放置在AutoCodePreviewReport里，因此需要执行该操作】
		AutoCodePreviewReportLike::$domain_files=AutoCodePreviewReport::$domain_files;
		AutoCodePreviewReportLike::$action_front_files=AutoCodePreviewReport::$action_front_files;
		AutoCodePreviewReportLike::$service_files=AutoCodePreviewReport::$service_files;
		AutoCodePreviewReportLike::$view_front_files=AutoCodePreviewReport::$view_front_files;
		AutoCodePreviewReportLike::$manage_service_file=AutoCodePreviewReport::$manage_service_file;

		AutoCodePreviewReportLike::$action_model_files=AutoCodePreviewReport::$action_model_files;
		AutoCodePreviewReportLike::$view_model_files=AutoCodePreviewReport::$view_model_files;
		AutoCodePreviewReportLike::$model_index_file=AutoCodePreviewReport::$model_index_file;
	}

	/**
	 * 用户输入需求
	 */
	public static function UserInput()
	{
		$default_dir=Gc::$nav_root_path."model".DS;
		self::$save_dir=$default_dir;

		self::init();
		$title="一键生成指定表前后台所有模板";
		$inputArr=array();
		foreach (self::$tableList as $tablename) {
			$inputArr[$tablename]=$tablename;
		}
		echo  "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>\r\n
				<html lang='zh-CN' xml:lang='zh-CN' xmlns='http://www.w3.org/1999/xhtml'>\r\n";
		echo "<head>\r\n";
		echo UtilCss::form_css()."\r\n";
		$url_base=UtilNet::urlbase();
		echo "</head>";
		echo "<body>";
		echo "<br/><br/><br/><h1 align='center'>$title</h1>\r\n";
		echo "<div align='center' height='450'>\r\n";
		echo "<form>\r\n";
		echo "  <div style='line-height:1.5em;'>\r\n";
		echo "      <label>输出文件路径:</label><input style='width:400px;text-align:left;padding-left:10px;' type='text' name='save_dir' value='$default_dir' id='save_dir' />\r\n";
		if (!empty($inputArr)){
			echo "<br/><br/>\r\n
					<label>&nbsp;&nbsp;&nbsp;选择需要生成的表:</label><select multiple='multiple' size='8' style='height:320px;' name='table_names[]'>\r\n";
			foreach ($inputArr as $key=>$value) {
				echo "        <option value='$key'>$value</option>\r\n";
			}
			echo "      </select>\r\n";
		}
		echo "  </div>\r\n";
		echo "  <input type='submit' value='生成' /><br/>\r\n";
		echo "</form>\r\n";
		echo "</div>\r\n";
		echo "</body>\r\n";
		echo "</html>";
	}

	/**
	 * 创建后台控制器类
	 * 包括【Action_Upload和Action_Gc::$appName】
	 * @param array|string $table_names
	 * 示例如下：
	 *  1.array:array('bb_user_admin','bb_core_blog')
	 *  2.字符串:'bb_user_admin,bb_core_blog'
	 */
	public static function createBgAction($table_names="")
	{
		$fieldInfos=self::fieldInfosByTable_names($table_names);
		$file_bg_action_appname=Gc::$nav_root_path.Gc::$module_root.DS.AutoCodePreviewReportLike::$bg_action_index_file;
		if(file_exists($file_bg_action_appname))
		{
			$content=file_get_contents($file_bg_action_appname);
			foreach ($fieldInfos as $tablename=>$fieldInfo)
			{
				$instancename=self::getInstancename($tablename);
				$section_content=AutoCodeAction::createBgActionIndex($tablename,$fieldInfo);
				if(contain($content,"public function $instancename()"))
				{
					$table_comment=self::tableCommentKey($tablename);
					$flag_a ="	/**\r\n";
					$flag_a.="	 * 控制器:$table_comment\r\n";
					$flag_a.="	 */\r\n";
					$ctrl=substr($content,0,strpos($content,$flag_a));
					if(empty($ctrl))$ctrl=substr($content,0,strpos($content,"public function $instancename()"));
					$content=substr($content,strpos($content,"public function $instancename()"));
					$ctrr=substr($content,strpos($content,"}")+3);
					$content=trim($ctrl)."\r\n".rtrim($section_content)."\r\n".$ctrr;
				}else{
					$ctrl=substr($content,0,strrpos($content,"}"));
					$ctrr=substr($content,strrpos($content,"}"));
					$content=trim($ctrl)."\r\n\r\n".rtrim($section_content)."\r\n".$ctrr;
				}
			}
			$file_bg_action_appname_model=self::$save_dir.AutoCodePreviewReportLike::$bg_action_index_file;
			file_put_contents($file_bg_action_appname_model, $content);
		}

		$file_bg_action_upload=Gc::$nav_root_path.Gc::$module_root.DS.AutoCodePreviewReportLike::$bg_action_upload_file;
		if(file_exists($file_bg_action_upload))
		{
			$content=file_get_contents($file_bg_action_upload);
			foreach ($fieldInfos as $tablename=>$fieldInfo)
			{
				$classname = self::getClassname($tablename);
				if(!contain($content,"public function upload$classname()"))
				{
					$section_content=AutoCodeAction::createBgActionUpload($tablename,$fieldInfo);
					$ctrl=substr($content,0,strrpos($content,"}"));
					$ctrr=substr($content,strrpos($content,"}"));
					$content=trim($ctrl)."\r\n\r\n".rtrim($section_content)."\r\n".$ctrr;
				}
			}
			$file_bg_action_upload_model=self::$save_dir.AutoCodePreviewReportLike::$bg_action_upload_file;
			file_put_contents($file_bg_action_upload_model, $content);
		}
	}

	/**
	 * 创建服务管理类
	 * @param array|string $table_names
	 * 示例如下：
	 *  1.array:array('bb_user_admin','bb_core_blog')
	 *  2.字符串:'bb_user_admin,bb_core_blog'
	 */
	public static function createManageService($table_names="")
	{
		$file_manage_service_file=Gc::$nav_root_path.Gc::$module_root.DS.AutoCodePreviewReportLike::$manage_service_file;
		if(file_exists($file_manage_service_file))
		{
			$tableList=self::tableListByTable_names($table_names);
			$content=file_get_contents($file_manage_service_file);
			foreach($tableList as $tablename){
				$result=AutoCodeService::createManageService($tablename);
				$section_define  = $result["section_define"];
				$section_content = $result["section_content"];

				if(!contain($content,$section_define)){
					$ctrl=substr($content,0,strpos($content, "	 * 提供服务:")-8);
					$ctrr=substr($content, strpos($content, "	 * 提供服务:")-8);
					$content=$ctrl.$section_define.$ctrr;
					$content=trim($content);
					$ctrl=substr($content,0,strrpos($content,"}"));
					$ctrr=substr($content,strrpos($content,"}"));
					$content=trim($ctrl)."\r\n\r\n".rtrim($section_content)."\r\n".$ctrr;
				}
			}
			$ffile_manage_service_file_model=self::$save_dir.AutoCodePreviewReportLike::$manage_service_file;
			file_put_contents($ffile_manage_service_file_model, $content);
		}
	}

	/**
	 * 创建后台服务管理类
	 * @param array|string $table_names
	 * 示例如下：
	 *  1.array:array('bb_user_admin','bb_core_blog')
	 *  2.字符串:'bb_user_admin,bb_core_blog'
	 */
	public static function createManageExtService($table_names="")
	{
		$file_manage_ext_service=Gc::$nav_root_path.Gc::$module_root.DS.AutoCodePreviewReportLike::$bg_manage_service_ext_file;
		if(file_exists($file_manage_ext_service))
		{
			$tableList=self::tableListByTable_names($table_names);
			$content=file_get_contents($file_manage_ext_service);
			foreach($tableList as $tablename){
				$result=AutoCodeService::createManageExtService($tablename);
				$section_define  = $result["section_define"];
				$section_content = $result["section_content"];

				if(!contain($content,$section_define)){
					$ctrl=substr($content,0,strpos($content, "	 * 提供服务:")-8);
					$ctrr=substr($content, strpos($content, "	 * 提供服务:")-8);
					$content=$ctrl.$section_define.$ctrr;
					$content=trim($content);
					$ctrl=substr($content,0,strrpos($content,"}"));
					$ctrr=substr($content,strrpos($content,"}"));
					$content=trim($ctrl)."\r\n\r\n".rtrim($section_content)."\r\n".$ctrr;
				}
			}
			$file_manage_ext_service_model=self::$save_dir.AutoCodePreviewReportLike::$bg_manage_service_ext_file;
			file_put_contents($file_manage_ext_service_model, $content);
		}
	}

	/**
	 * 生成后台服务配置文件:service.config.xml
	 * @param array|string $table_names
	 * 示例如下：
	 *  1.array:array('bb_user_admin','bb_core_blog')
	 *  2.字符串:'bb_user_admin,bb_core_blog'
	 */
	public static function createServiceXml($table_names="")
	{
		$file_bg_service_xml_file=Gc::$nav_root_path.Gc::$module_root.DS.AutoCodePreviewReportLike::$bg_service_xml_file;
		if(file_exists($file_bg_service_xml_file))
		{
			$tableList=self::tableListByTable_names($table_names);
			$content=file_get_contents($file_bg_service_xml_file);
			$oldcontent=$content;
			foreach($tableList as $tablename){
				$classname=self::getClassname($tablename);
				if(!contain($content,"<service name=\"ExtService{$classname}\">")){
					$section_content=AutoCodeService::createServiceXml($tablename);
					$ctrl=substr($content,0,strrpos($content, "</service>")+12);
					$ctrr=substr($content, strrpos($content,"</service>")+12);
					$content=$ctrl."\r\n".$section_content.ltrim($ctrr);
				}
			}
			if($content!=$oldcontent){
				$ctrl=substr($content,0,strrpos($content, "</service>")+12);
				$ctrr=substr($content,strrpos($content,"</service>")+14);
				$content=$ctrl.$ctrr;
			}
			$file_bg_service_xml_file_model=self::$save_dir.AutoCodePreviewReportLike::$bg_service_xml_file;
			file_put_contents($file_bg_service_xml_file_model, $content);
		}
	}

	/**
	 * 生成后台服务配置文件:service.config.xml
	 * @param array|string $table_names
	 * 示例如下：
	 *  1.array:array('bb_user_admin','bb_core_blog')
	 *  2.字符串:'bb_user_admin,bb_core_blog'
	 */
	public static function createMenuConfigXml($table_names="")
	{
		$file_bg_menu_xml_file=Gc::$nav_root_path.Gc::$module_root.DS.AutoCodePreviewReportLike::$bg_menu_xml_file;
		if(file_exists($file_bg_menu_xml_file))
		{
			$tableList=self::tableListByTable_names($table_names);
			$content=file_get_contents($file_bg_menu_xml_file);
			$appName=Gc::$appName;
			foreach($tableList as $tablename){
				$instancename=self::getInstancename($tablename);
				if(!contain($content,"id=\"$instancename\"")){
					$table_comment=self::tableCommentKey($tablename);
					$section_content="		<menu name=\"$table_comment\" id=\"$instancename\" address=\"index.php?go=admin.$appName.{$instancename}\" />";
					$ctrl=substr($content,0,strrpos($content, "/>")+2);
					$ctrr=substr($content, strrpos($content,"/>")+2);
					$content=$ctrl."\r\n".$section_content.$ctrr;
				}
			}
			$file_bg_menu_xml_file_model=self::$save_dir.AutoCodePreviewReportLike::$bg_menu_xml_file;
			file_put_contents($file_bg_menu_xml_file_model, $content);
		}
	}

	/**
	 * 生成后台服务配置文件:service.config.xml
	 * @param array|string $table_names
	 * 示例如下：
	 *  1.array:array('bb_user_admin','bb_core_blog')
	 *  2.字符串:'bb_user_admin,bb_core_blog'
	 */
	public static function createModelIndex($table_names="")
	{
		$file_model_index_file=Gc::$nav_root_path.Gc::$module_root.DS.AutoCodePreviewReportLike::$model_index_file;
		if(file_exists($file_model_index_file))
		{
			$tableList=self::tableListByTable_names($table_names);
			$content=file_get_contents($file_model_index_file);
			$appname=AutoCodePreviewReportLike::$m_model;
			foreach($tableList as $tablename){
				$instancename=self::getInstancename($tablename);
				if(!contain($content,"go=model.$instancename.lists")){
					$table_comment=self::tableCommentKey($tablename);
					$section_content="		<tr class=\"entry\"><td class=\"content\"><a href=\"{\$url_base}index.php?go={$appname}.{$instancename}.lists\">{$table_comment}</a></td></tr>\r\n";
					$ctrl=substr($content,0,strrpos($content, "</tr>")+5);
					$ctrr=substr($content, strrpos($content,"</tr>")+5);
					$content=$ctrl."\r\n".$section_content.$ctrr;
				}
			}
			$file_model_index_file_model=self::$save_dir.AutoCodePreviewReportLike::$model_index_file;
			file_put_contents($file_model_index_file_model, $content);
		}
	}

	/**
	 * 覆盖原文件内容
	 * @param array $files 需覆盖的文件
	 * @param string $model_save_dir 模板文件存储的路径
	 */
	public static function overwrite($files,$model_save_dir)
	{
		$overwrite_not_arr=array();//发现Mac电脑因为权限不能写文件需提示
		foreach ($files as $file)
		{
			$file_overwrite=Gc::$nav_root_path.Gc::$module_root.DS.$file;
			$content=file_get_contents($model_save_dir.$file);
			$dir_overwrite=dirname($file_overwrite);
			UtilFileSystem::createDir($dir_overwrite);
			file_put_contents($file_overwrite, $content) or
			$overwrite_not_arr[]=$dir_overwrite;
		}
		if(count($overwrite_not_arr)>0){
			$overwrite_not_dir_str="";
			foreach ($overwrite_not_arr as $overwrite_not_dir) {
				if (contain(strtolower(php_uname()),"darwin")){
					$overwrite_not_dir_str.="sudo mkdir -p ".$overwrite_not_dir."<br/>".str_repeat("&nbsp;",8).
					"sudo chmod -R 0777 ".$overwrite_not_dir."<br/>".str_repeat("&nbsp;",8);
				}else{
					$overwrite_not_dir_str.="sudo mkdir -p ".$overwrite_not_dir."<br/>".str_repeat("&nbsp;",8).
						"sudo chown -R www-data:www-data ".$overwrite_not_dir."<br/>".str_repeat("&nbsp;",8).
						"sudo chmod -R 0755 ".$overwrite_not_dir."<br/>".str_repeat("&nbsp;",8);
				}
			}
			die("<p style='font: 15px/1.5em Arial;margin:15px;line-height:2em;'>因为安全原因，需要手动在操作系统中创建目录<br/>".
				"Linux系统需要执行指令:<br/>".str_repeat("&nbsp;",8).
				$overwrite_not_dir_str."</p>");
		}

	}

}

?>
