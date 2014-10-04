<?php
/**
 +---------------------------------<br/>
 * 工具类:自动生成代码-生成单张表或者对应类的前后台所有模板文件<br/>
 +---------------------------------<br/>
 * @category betterlife
 * @package core.autoCode
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCodeModel extends AutoCode
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
		$dest_directory=Gc::$nav_root_path."tools".DIRECTORY_SEPARATOR."tools".DIRECTORY_SEPARATOR."autoCode".DIRECTORY_SEPARATOR;
		$filename=$dest_directory."autocode.config.xml";
		AutoCodeValidate::run($table_names);
		if (!file_exists($filename)){
			AutoCodeConfig::run($table_names);
			die("&nbsp;&nbsp;自动生成代码的配置文件已生成，请再次运行以生成所有web应用代码！");
		}
		self::$showReport.=AutoCodeFoldHelper::foldEffectReady();
		//生成实体数据对象类
		AutoCodeDomain::$save_dir =self::$save_dir;
		AutoCodeDomain::$type     =2;
		self::$showReport.=AutoCodeFoldHelper::foldbeforedomain();
		AutoCodeDomain::AutoCode($table_names);
		self::$showReport.=AutoCodeFoldHelper::foldafterdomain();
		AutoCode::$isOutputCss=true;

		//生成提供服务类[前端和后端基于Ext的Service类]
		AutoCodeService::$save_dir =self::$save_dir;
		self::$showReport.=AutoCodeFoldHelper::foldbeforeservice();
		AutoCodeService::$type     =2;
		AutoCodeService::AutoCode($table_names);
		AutoCodeService::$type     =3;
		AutoCodeService::AutoCode($table_names);
		self::$showReport.=AutoCodeFoldHelper::foldafterservice();

		//生成Action类[前端和后端]
		AutoCodeAction::$save_dir =self::$save_dir;
		self::$showReport.=AutoCodeFoldHelper::foldbeforeaction();
		AutoCodeAction::$type     =0;
		AutoCodeAction::AutoCode($table_names);
		AutoCodeAction::$type     =1;
		AutoCodeAction::AutoCode($table_names);
		AutoCodeAction::$type     =2;
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

		//生成后端表示层
		AutoCodeViewExt::$save_dir =self::$save_dir;
		self::$showReport.=AutoCodeFoldHelper::foldbeforeviewext();
		AutoCodeViewExt::AutoCode($table_names);
		self::$showReport.=AutoCodeFoldHelper::foldafterviewext();
		self::$showReport.= "</div>";

		//将新添加的内容放置在文件最后作为可覆盖的内容
		AutoCodePreviewReport::init();
		self::createManageExtService($table_names);
		self::createServiceXml($table_names);

		//echo self::$showReport;
	}

	/**
	 * 用户输入需求
	 */
	public static function UserInput($title=null,$inputArr=null)
	{

		$default_dir=Gc::$nav_root_path."model".DIRECTORY_SEPARATOR;
		self::$save_dir=$default_dir;
		self::init();
		$title="一键生成指定表前后台所有模板";
		$inputArr=array();
		foreach (self::$tableList as $tablename) {
			$inputArr[$tablename]=$tablename;
		}
		echo  "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\r\n
				<html lang=\"zh-CN\" xml:lang=\"zh-CN\" xmlns=\"http://www.w3.org/1999/xhtml\">\r\n";
		echo "<head>\r\n";
		echo UtilCss::form_css()."\r\n";
		$url_base=UtilNet::urlbase();
		echo "</head>";
		echo "<body>";
		echo "<br/><br/><br/><h1 align='center'>$title</h1>\r\n";
		echo "<div align='center' height='450'>\r\n";
		echo "<form>\r\n";
		echo "  <div style='line-height:1.5em;'>\r\n";
		echo "      <label>输出文件路径:</label><input style=\"width:400px;text-align:left;padding-left:10px;\" type=\"text\" name=\"save_dir\" value=\"$default_dir\" id=\"save_dir\" />\r\n";
		if (!empty($inputArr)){
			echo "<br/><br/>\r\n
					<label>&nbsp;&nbsp;&nbsp;选择需要生成的表:</label><select multiple='multiple' size='8' style='height:320px;' name=\"table_names[]\">\r\n";
			foreach ($inputArr as $key=>$value) {
				echo "        <option value='$key'>$value</option>\r\n";
			}
			echo "      </select>\r\n";
		}
		echo "  </div>\r\n";
		echo "  <input type=\"submit\" value='生成' /><br/>\r\n";
		echo "</form>\r\n";
		echo "</div>\r\n";
		echo "</body>\r\n";
		echo "</html>";
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
		$file_manage_ext_service=Gc::$nav_root_path.Gc::$module_root.DIRECTORY_SEPARATOR.AutoCodePreviewReport::$bg_manage_service_ext_file;
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
			$file_manage_ext_service_model=self::$save_dir.AutoCodePreviewReport::$bg_manage_service_ext_file;
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
		$file_bg_service_xml_file=Gc::$nav_root_path.Gc::$module_root.DIRECTORY_SEPARATOR.AutoCodePreviewReport::$bg_service_xml_file;
		if(file_exists($file_bg_service_xml_file))
		{
			$tableList=self::tableListByTable_names($table_names);
			$content=file_get_contents($file_bg_service_xml_file);
			foreach($tableList as $tablename){
				$section_content=AutoCodeService::createServiceXml($tablename);
				$classname=self::getClassname($tablename);
				if(!contain($content,"<service name=\"ExtService{$classname}\">")){
					$ctrl=substr($content,0,strrpos($content, "</service>")+12);
					$ctrr=substr($content, strrpos($content,"</service>")+12);
					$content=$ctrl."\r\n".$section_content.ltrim($ctrr);
				}
			}
			$ctrl=substr($content,0,strrpos($content, "</service>")+12);
			$ctrr=substr($content, strrpos($content,"</service>")+14);
			$content=$ctrl.$ctrr;
			$file_bg_service_xml_file_model=self::$save_dir.AutoCodePreviewReport::$bg_service_xml_file;
			file_put_contents($file_bg_service_xml_file_model, $content);
		}
	}



	/**
	 * 覆盖原文件内容
	 * @param array $files 需覆盖的文件
	 * @param string $model_save_dir 模板文件存储的路径
	 */
	public static function overwrite($files,$model_save_dir)
	{
		foreach ($files as $file)
		{
			$file_overwrite=Gc::$nav_root_path.Gc::$module_root.DIRECTORY_SEPARATOR.$file;
			$content=file_get_contents($model_save_dir.$file);
			file_put_contents($file_overwrite, $content);
		}

	}

}

?>
