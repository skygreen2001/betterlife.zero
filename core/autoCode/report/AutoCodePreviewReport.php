<?php
/**
 +---------------------------------<br/>
 * 辅助工具类:自动生成代码<br/>
 * 可以预览生成代码的报告列表<br/>
 +---------------------------------<br/>
 * @category betterlife
 * @package core.autoCode
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCodePreviewReport extends AutoCode
{
	public static $domain_files=array();
	public static $enum_files=array();
	public static $action_front_files=array();
	public static $action_model_files=array();
	public static $service_files=array();
	public static $service_bg_files=array();
	public static $view_front_files=array();
	public static $view_model_files=array();
	public static $view_bg_files=array();
	public static $bg_ext_js_files=array();
	public static $bg_ajax_php_files=array();
	public static $bg_action_index_file="";
	public static $bg_action_upload_file="";
	public static $bg_service_xml_file="";
	public static $bg_menu_xml_file="";
	public static $bg_manage_service_ext_file="";
	public static $manage_service_file="";

	/**
	 * 初始化
	 */
	public static function init()
	{
		self::$manage_service_file=self::$save_dir.Gc::$appName.DIRECTORY_SEPARATOR.self::$dir_src.DIRECTORY_SEPARATOR.AutoCodeService::$service_dir.DIRECTORY_SEPARATOR."Manager_Service.php";
		$category_cap=Gc::$appName;
		$category_cap{0}=ucfirst($category_cap{0});
		self::$bg_action_index_file=self::$save_dir."admin".DIRECTORY_SEPARATOR.AutoCodeAction::$action_dir.DIRECTORY_SEPARATOR."Action_".$category_cap.".php";
		self::$bg_action_upload_file=self::$save_dir."admin".DIRECTORY_SEPARATOR.AutoCodeAction::$action_dir.DIRECTORY_SEPARATOR."Action_Upload.php";
		self::$bg_manage_service_ext_file="admin".DIRECTORY_SEPARATOR.self::$dir_src.DIRECTORY_SEPARATOR.AutoCodeService::$service_dir.DIRECTORY_SEPARATOR.AutoCodeService::$ext_dir.DIRECTORY_SEPARATOR."Manager_ExtService.php";
		self::$bg_service_xml_file="admin".DIRECTORY_SEPARATOR.self::$dir_src.DIRECTORY_SEPARATOR.AutoCodeService::$service_dir.DIRECTORY_SEPARATOR."service.config.xml";
		self::$bg_menu_xml_file=self::$save_dir."admin".DIRECTORY_SEPARATOR.self::$dir_src.DIRECTORY_SEPARATOR."view".DIRECTORY_SEPARATOR."menu".DIRECTORY_SEPARATOR."menu.config.xml";
	}

	/**
	 * 显示报告
	 */
	public static function showReport()
	{
		$file ="C:\wamp\www\betterlife\model\betterlife\src\domain\core\Blog.php";
		$origin_file="C:\wamp\www\betterlife\home\betterlife\src\domain\core\Blog.php";
		$url_base=Gc::$url_base;
		$url_base=substr($url_base,0,strlen($url_base)-1);

		$title_model=<<<MODEL
	<tr class="overwrite"><td colspan="3">[title]</td></tr>
MODEL;
		$model=<<<MODEL
	<tr class="overwrite">
		<td class="confirm">[status]<input type="checkbox" name="overwrite[]" value="[relative_file]" /></td>
		<td class="file" style="max-width: 720px;word-wrap: break-word;">
		  <a target="_blank" href="$url_base/tools/file/viewfilebyline.php?f=[file]&l=false">[file]</a>
		</td>
		<td><a href="$url_base/tools/file/viewfilebyline.php?f=[file]" target='_blank'>查看</a>|<a href="$url_base/tools/file/editfile.php?f=[file]" target='_blank'>编辑</a>|<a href="$url_base/tools/file/diff.php?old_file=[origin_file]&new_file=[file]" target="_blank">比较差异</a></td>
	</tr>
MODEL;
		$status=array("<font color='red'>[会覆盖]</font>","<font color='green'>[新生成]</font>","[未修改]");

		$title="[前台]实体数据对象类";
		$moreContent=str_replace("[title]",$title,$title_model);
		//生成实体数据对象
		foreach (self::$domain_files as $file) {
			$file_content=str_replace("[file]", self::$save_dir.$file, $model);
			$origin_file= Gc::$nav_root_path.Gc::$module_root.DIRECTORY_SEPARATOR.$file;
			$file_content=str_replace("[origin_file]",$origin_file, $file_content);
			$file_content=str_replace("[relative_file]",$file, $file_content);
			if(file_exists($origin_file)){
				$file_content=str_replace("[status]",$status[0], $file_content);
			}else{
				$file_content=str_replace("[status]",$status[1], $file_content);
			}
			$moreContent.=$file_content;
		}
		//生成枚举类型
		if(self::$enum_files&&(count(self::$enum_files)>0)){
			$title="[前台]枚举类型类";
			$moreContent.=str_replace("[title]",$title,$title_model);
		}
		foreach (self::$enum_files as $file) {
			$file_content=str_replace("[file]", self::$save_dir.$file, $model);
			$origin_file= Gc::$nav_root_path.Gc::$module_root.DIRECTORY_SEPARATOR.$file;
			$file_content=str_replace("[origin_file]",$origin_file, $file_content);
			$file_content=str_replace("[relative_file]",$file, $file_content);
			if(file_exists($origin_file)){
				$file_content=str_replace("[status]",$status[0], $file_content);
			}else{
				$file_content=str_replace("[status]",$status[1], $file_content);
			}
			$moreContent.=$file_content;
		}

		//生成使用ExtJs框架的Service[后台]文件
		if(self::$service_bg_files&&(count(self::$service_bg_files)>0)){
			$title="[后台]使用ExtJs框架的Service文件";
			$moreContent.=str_replace("[title]",$title,$title_model);
		}
		foreach (self::$service_bg_files as $file) {
			$file_content=str_replace("[file]", self::$save_dir.$file, $model);
			$origin_file= Gc::$nav_root_path.Gc::$module_root.DIRECTORY_SEPARATOR.$file;
			$file_content=str_replace("[origin_file]",$origin_file, $file_content);
			$file_content=str_replace("[relative_file]",$file, $file_content);
			if(file_exists($origin_file)){
				$file_content=str_replace("[status]",$status[0], $file_content);
			}else{
				$file_content=str_replace("[status]",$status[1], $file_content);
			}
			$moreContent.=$file_content;
		}

		//生成后台管理服务类
		$title="[后台]服务管理类";
		$moreContent.=str_replace("[title]",$title,$title_model);
		$file=self::$bg_manage_service_ext_file;
		$file_content=str_replace("[file]", self::$save_dir.$file, $model);
		$origin_file= Gc::$nav_root_path.Gc::$module_root.DIRECTORY_SEPARATOR.$file;
		$file_content=str_replace("[origin_file]",$origin_file, $file_content);
		$file_content=str_replace("[relative_file]",$file, $file_content);
		$file_content_old=file_get_contents($origin_file);
		$file_content_new=file_get_contents(self::$save_dir.$file);
		if($file_content_old==$file_content_new){
			$file_content=str_replace("[status]",$status[2], $file_content);
		}else{
			$file_content=str_replace("[status]","<font color='green'>[新增加]</font>", $file_content);
		}
		$moreContent.=$file_content;

		//生成后台服务配置文件:service.config.xml
		$title="[后台]服务配置文件";
		$moreContent.=str_replace("[title]",$title,$title_model);
		$file=self::$bg_service_xml_file;
		$file_content=str_replace("[file]", self::$save_dir.$file, $model);
		$origin_file= Gc::$nav_root_path.Gc::$module_root.DIRECTORY_SEPARATOR.$file;
		$file_content=str_replace("[origin_file]",$origin_file, $file_content);
		$file_content=str_replace("[relative_file]",$file, $file_content);
		$file_content=str_replace("[status]","<font color='green'>[新增加]</font>", $file_content);
		$moreContent.=$file_content;

		//生成标准方法的Service文件
		if(self::$service_files&&(count(self::$service_files)>0)){
			$title="[前台]标准方法的Service文件";
			$moreContent.=str_replace("[title]",$title,$title_model);
		}
		foreach (self::$service_files as $file) {
			$file_content=str_replace("[file]", self::$save_dir.$file, $model);
			$origin_file= Gc::$nav_root_path.Gc::$module_root.DIRECTORY_SEPARATOR.$file;
			$file_content=str_replace("[origin_file]",$origin_file, $file_content);
			$file_content=str_replace("[relative_file]",$file, $file_content);
			if(file_exists($origin_file)){
				$file_content=str_replace("[status]",$status[0], $file_content);
			}else{
				$file_content=str_replace("[status]",$status[1], $file_content);
			}
			$moreContent.=$file_content;
		}



		$save_dir=self::$save_dir;
		$showResult=<<<REPORT
<style type="text/css">
	table.preview td {
		border: 1px solid #529ec6;
		text-align:center;
	}
	table.preview {
		border-collapse: collapse;
	}
	table {
		margin-bottom: 1.4em;
		width: 80%;
	}
	table, td, th {
		vertical-align: middle;
	}
	table {
		border-collapse: separate;
		border-spacing: 0;
	}
	table.preview, table.preview th,table.preview td {
		border: 1px solid #529ec6;
	}
	table.preview th {
		text-align: center;
	}
	caption {
		padding: 4px 10px 4px 5px;
	}
	th {
		font-weight: bold;
	}
	table, td, th {
		vertical-align: middle;
	}
</style>
<script language="JavaScript">
function toggle(source) {
	checkboxes = document.getElementsByName('overwrite[]');
	for(var i=0, n=checkboxes.length;i<n;i++) {
		checkboxes[i].checked = source.checked;
	}
}
</script>
<div align="center">
<form><input type="hidden" name="model_save_dir" value="$save_dir" />
<table class="preview">
  <tbody>
	<tr>
		<th class="confirm">全部覆盖<input type="checkbox" id="overwrite" name="overwrite[]" onclick="toggle(this)"></th>
		<th class="file">文件路径</th>
		<th class="file">操作</th>
	</tr>
$moreContent
  </tbody>
</table>
	<input type="submit" value='覆盖生成' />
</form>
</div>
REPORT;
		return $showResult;
	}
}
?>