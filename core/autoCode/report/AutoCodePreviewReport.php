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
		self::$bg_manage_service_ext_file=self::$save_dir."admin".DIRECTORY_SEPARATOR.self::$dir_src.DIRECTORY_SEPARATOR.AutoCodeService::$service_dir.DIRECTORY_SEPARATOR.AutoCodeService::$ext_dir.DIRECTORY_SEPARATOR."Manager_ExtService.php";
		self::$bg_service_xml_file=self::$save_dir."admin".DIRECTORY_SEPARATOR.self::$dir_src.DIRECTORY_SEPARATOR.AutoCodeService::$service_dir.DIRECTORY_SEPARATOR."service.config.xml";
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
		$title="生成实体数据对象类";
		$title_model=<<<MODEL
	<tr class="overwrite"><td colspan="3">$title</td></tr>
MODEL;
		$model=<<<MODEL
	<tr class="overwrite">
		<td class="confirm">覆盖<input type="checkbox" name="overwrite[]" /></td>
		<td class="file">
		  <a target="_blank" href="$url_base/tools/file/viewfilebyline.php?f=[file]&l=false">[file]</a>
		</td>
		<td>[<a href="$url_base/tools/file/viewfilebyline.php?f=[file]" target='_blank'>查看</a>]|[<a href="$url_base/tools/file/editfile.php?f=[file]" target='_blank'>编辑</a>]|[<a href="$url_base/tools/file/diff.php?old_file=[origin_file]&new_file=[file]" target="_blank">比较差异</a>]</td>
	</tr>
MODEL;
		$moreContent=$title_model;
		foreach (self::$domain_files as $file) {
			$file_content=str_replace("[file]", self::$save_dir.$file, $model);
			$file_content=str_replace("[origin_file]", Gc::$nav_root_path.Gc::$module_root.DIRECTORY_SEPARATOR.$file, $file_content);
			$moreContent.=$file_content;
		}

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
<div align="center">
<table class="preview">
  <tbody>
	<tr>
		<th class="confirm">全部覆盖<input type="checkbox" id="overwrite" name="overwrite[]" value="1"></th>
		<th class="file">文件路径</th>
		<th class="file">操作</th>
	</tr>
$moreContent
  </tbody>
</table>
<input type="submit" value='覆盖生成' />
</div>
REPORT;
		return $showResult;
	}
	/**
	 * 显示两个文件的差别
	 * @param string $origin_file 源文件路径
	 * @param string $new_file 新文件路径
	 */
	public static function showDiff($origin_file,$new_file)
	{

	}
}
?>