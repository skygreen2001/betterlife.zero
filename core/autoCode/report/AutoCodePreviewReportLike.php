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
class AutoCodePreviewReportLike extends AutoCode
{
	/**
	 * 应用:后台名称
	 */
	public static $m_bg="admin";
	/**
	 * 应用:模板名称
	 */
	public static $m_model="model";
	/**
	 * 第一次运行
	 */
	public static $is_first_run=true;
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
	public static $model_index_file="";

	/**
	 * 初始化
	 */
	public static function init()
	{
		self::$manage_service_file=Gc::$appName.DS.self::$dir_src.DS.AutoCodeService::$service_dir.DS."Manager_Service.php";
		$category_cap=Gc::$appName;
		$category_cap{0}=ucfirst($category_cap{0});
		self::$bg_action_index_file=self::$m_bg.DS.AutoCodeAction::$action_dir.DS."Action_".$category_cap.".php";
		self::$bg_action_upload_file=self::$m_bg.DS.AutoCodeAction::$action_dir.DS."Action_Upload.php";
		self::$bg_manage_service_ext_file=self::$m_bg.DS.self::$dir_src.DS.AutoCodeService::$service_dir.DS.AutoCodeService::$ext_dir.DS."Manager_ExtService.php";
		self::$bg_service_xml_file=self::$m_bg.DS.self::$dir_src.DS.AutoCodeService::$service_dir.DS."service.config.xml";
		self::$bg_menu_xml_file=self::$m_bg.DS.self::$dir_src.DS."view".DS."menu".DS."menu.config.xml";
		self::$model_index_file=self::$m_model.DS.Config_F::VIEW_VIEW.DS.Gc::$self_theme_dir.DS.Config_F::VIEW_CORE.DS."index".DS."index".Config_F::SUFFIX_FILE_TPL;
	}

	/**
	 * 显示报告
	 * @param array|string $table_names
	 * 示例如下：
	 *  1.array:array('bb_user_admin','bb_core_blog')
	 *  2.字符串:'bb_user_admin,bb_core_blog'
	 */
	public static function showReport($table_names="")
	{
		$file ="";
		$origin_file="";
		$url_base=Gc::$url_base;
		$dir_autocode=$url_base."tools/tools/autoCode";
		$layer_autocode=$dir_autocode."/layer";
		$url_base=substr($url_base,0,strlen($url_base)-1);

		$module_model=<<<MODEL
	<tr class="overwrite" style="background-color: green;color:white;"><td><input type="checkbox" [checked] id="select[module_name]" name="select[module_name]"  onclick="toggle[module_name](this)" /></td><td colspan="2">[title]</td></tr>
MODEL;

		$title_model=<<<MODEL
	<tr class="overwrite"><td colspan="3">[title]</td></tr>
MODEL;
		$model=<<<MODEL
	<tr class="overwrite">
		<td class="confirm">[status]<input type="checkbox" [checked] name="overwrite[module_name][]" value="[relative_file]" /></td>
		<td class="file" style="max-width: 720px;word-wrap: break-word;">
		  <a target="_blank" href="$url_base/tools/file/viewfilebyline.php?f=[file]&l=false">[file]</a>
		</td>
		<td><a href="$url_base/tools/file/viewfilebyline.php?f=[file]" target='_blank'>查看</a>|<a href="$url_base/tools/file/editfile.php?f=[file]" target='_blank'>编辑</a>|<a href="$url_base/tools/file/diff.php?old_file=[origin_file]&new_file=[file]" target="_blank">比较差异</a></td>
	</tr>
MODEL;
		$status=array("<font color='red'>[会覆盖]</font>","<font color='green'>[新生成]</font>","[未修改]");

		$title="<a href='$layer_autocode/domain/db_domain.php' target='_blank' style='color:white;'>数据模型<Domain|Model></a>";
		$moreContent=str_replace("[title]",$title,$module_model);
		if(self::$is_first_run){
			$moreContent=str_replace("[checked]","checked", $moreContent);
		}else{
			$moreContent=str_replace("[checked]","", $moreContent);
		}
		$moreContent=str_replace("[module_name]","domain",$moreContent);

		$title="<a href='$layer_autocode/domain/db_domain.php' target='_blank'>实体数据对象类</a>";
		$moreContent.=str_replace("[title]",$title,$title_model);
		//[前台]生成实体数据对象
		foreach (self::$domain_files as $file) {
			$file_content=str_replace("[file]", self::$save_dir.$file, $model);
			$origin_file= Gc::$nav_root_path.Gc::$module_root.DS.$file;
			$file_content=str_replace("[origin_file]",$origin_file, $file_content);
			$file_content=str_replace("[relative_file]",$file, $file_content);
			if(file_exists($origin_file)){
				$file_content_old=file_get_contents($origin_file);
				$file_content_new=file_get_contents(self::$save_dir.$file);
				if($file_content_old==$file_content_new){
					$file_content=str_replace("[status]",$status[2], $file_content);
				}else{
					$file_content=str_replace("[status]",$status[0], $file_content);
				}
			}else{
				$file_content=str_replace("[status]",$status[1], $file_content);
			}
			if(self::$is_first_run){
				$file_content=str_replace("[checked]","checked", $file_content);
			}else{
				$file_content=str_replace("[checked]","", $file_content);
			}
			$file_content=str_replace("[module_name]","domain",$file_content);
			$moreContent.=$file_content;
		}
		//[前台]生成枚举类型
		if(self::$enum_files&&(count(self::$enum_files)>0)){
			$title="<a href='$layer_autocode/db_domain.php' target='_blank'>枚举类型类</a>";
			$moreContent.=str_replace("[title]",$title,$title_model);
		}
		foreach (self::$enum_files as $file) {
			$file_content=str_replace("[file]", self::$save_dir.$file, $model);
			$origin_file= Gc::$nav_root_path.Gc::$module_root.DS.$file;
			$file_content=str_replace("[origin_file]",$origin_file, $file_content);
			$file_content=str_replace("[relative_file]",$file, $file_content);
			if(file_exists($origin_file)){
				$file_content_old=file_get_contents($origin_file);
				$file_content_new=file_get_contents(self::$save_dir.$file);
				if($file_content_old==$file_content_new){
					$file_content=str_replace("[status]",$status[2], $file_content);
				}else{
					$file_content=str_replace("[status]",$status[0], $file_content);
				}
			}else{
				$file_content=str_replace("[status]",$status[1], $file_content);
			}
			if(self::$is_first_run){
				$file_content=str_replace("[checked]","checked", $file_content);
			}else{
				$file_content=str_replace("[checked]","", $file_content);
			}
			$file_content=str_replace("[module_name]","domain",$file_content);
			$moreContent.=$file_content;
		}

		$admin_module=Gc::$nav_root_path.Gc::$module_root.DS."admin".DS;
		if(is_dir($admin_module)){
			//修改model文件夹名称为后台文件夹admin
			$old_admin_name=self::$save_dir.DS."admin".DS;
			if(is_dir($old_admin_name))UtilFileSystem::deleteDir($old_admin_name);
			$old_model_name=self::$save_dir.DS."model".DS;
			$new_model_name=self::$save_dir.DS."admin".DS;
			if(is_dir($old_model_name))rename($old_model_name,$new_model_name);

			$title="<a href='$dir_autocode/db_all.php' target='_blank' style='color:white;'>[后台通用模板]</a>";
			$moreContent.=str_replace("[title]",$title,$module_model);
			$moreContent=str_replace("[module_name]","model",$moreContent);
			$moreContent=str_replace("[checked]","", $moreContent);

			// 生成标准的增删改查模板Action，继承基本Action
			if(self::$action_model_files&&(count(self::$action_model_files)>0)){
				$title="<a href='$layer_autocode/db_action.php?type=1' target='_blank'>控制器</a>";
				$moreContent.=str_replace("[title]",$title,$title_model);
			}
			foreach (self::$action_model_files as $file) {
				$file=str_replace("model".DS,"admin".DS,$file);
				$file_content=str_replace("[file]", self::$save_dir.$file, $model);
				$origin_file= Gc::$nav_root_path.Gc::$module_root.DS.$file;
				$file_content=str_replace("[origin_file]",$origin_file, $file_content);
				$file_content=str_replace("[relative_file]",$file, $file_content);
				if(file_exists($origin_file)){
					$file_content_old=file_get_contents($origin_file);
					$file_content_new=file_get_contents(self::$save_dir.$file);
					if($file_content_old==$file_content_new){
						$file_content=str_replace("[status]",$status[2], $file_content);
					}else{
						$file_content=str_replace("[status]",$status[0], $file_content);
					}
				}else{
					$file_content=str_replace("[status]",$status[1], $file_content);
				}
				$file_content=str_replace("[checked]","", $file_content);
				$file_content=str_replace("[module_name]","model",$file_content);
				$moreContent.=$file_content;
			}

			//生成首页
			$title="<a href='$layer_autocode/view/db_view_default.php?type=1' target='_blank'>模板首页</a>";
			$moreContent.=str_replace("[title]",$title,$title_model);
			$file=self::$model_index_file;
			$file=str_replace("model".DS,"admin".DS,$file);
			$file_content=str_replace("[file]", self::$save_dir.$file, $model);
			$origin_file= Gc::$nav_root_path.Gc::$module_root.DS.$file;
			$file_content=str_replace("[origin_file]",$origin_file, $file_content);
			$file_content=str_replace("[relative_file]",$file, $file_content);
			$file_content_old=file_get_contents($origin_file);
			$file_content_new=file_get_contents(self::$save_dir.$file);
			if($file_content_old==$file_content_new){
				$file_content=str_replace("[status]",$status[2], $file_content);
			}else{
				$file_content=str_replace("[status]","<font color='green'>[新增加]</font>", $file_content);
			}
			$file_content=str_replace("[checked]","", $file_content);
			$file_content=str_replace("[module_name]","model",$file_content);
			$moreContent.=$file_content;

			// 生成标准的增删改查模板表示层页面
			if(self::$view_model_files&&(count(self::$view_model_files)>0)){
				$title="<a href='$layer_autocode/view/db_view_default.php?type=1' target='_blank'>表示层页面</a>";
				$moreContent.=str_replace("[title]",$title,$title_model);
			}
			foreach (self::$view_model_files as $file) {
				$file=str_replace("model".DS,"admin".DS,$file);
				$file_content=str_replace("[file]", self::$save_dir.$file, $model);
				$origin_file= Gc::$nav_root_path.Gc::$module_root.DS.$file;
				$file_content=str_replace("[origin_file]",$origin_file, $file_content);
				$file_content=str_replace("[relative_file]",$file, $file_content);
				if(file_exists($origin_file)){
					$file_content_old=file_get_contents($origin_file);
					$file_content_new=file_get_contents(self::$save_dir.$file);
					if($file_content_old==$file_content_new){
						$file_content=str_replace("[status]",$status[2], $file_content);
					}else{
						$file_content=str_replace("[status]",$status[0], $file_content);
					}
				}else{
					$file_content=str_replace("[status]",$status[1], $file_content);
				}
				$file_content=str_replace("[checked]","", $file_content);
				$file_content=str_replace("[module_name]","model",$file_content);
				$moreContent.=$file_content;
			}
		}

		if (Config_AutoCode::SHOW_REPORT_FRONT)
		{
			$title="<a href='$dir_autocode/db_all.php' target='_blank' style='color:white;'>[前台]</a>";
			$moreContent.=str_replace("[title]",$title,$module_model);
			$moreContent=str_replace("[module_name]","front",$moreContent);
			$moreContent=str_replace("[checked]","", $moreContent);

			//生成标准方法的Service文件
			if(self::$service_files&&(count(self::$service_files)>0)){
				$title="<a href='$layer_autocode/db_service.php?type=2' target='_blank'>标准方法的服务层文件</a>";
				$moreContent.=str_replace("[title]",$title,$title_model);
			}
			foreach (self::$service_files as $file) {
				$file_content=str_replace("[file]", self::$save_dir.$file, $model);
				$origin_file= Gc::$nav_root_path.Gc::$module_root.DS.$file;
				$file_content=str_replace("[origin_file]",$origin_file, $file_content);
				$file_content=str_replace("[relative_file]",$file, $file_content);
				if(file_exists($origin_file)){
					$file_content_old=file_get_contents($origin_file);
					$file_content_new=file_get_contents(self::$save_dir.$file);
					if($file_content_old==$file_content_new){
						$file_content=str_replace("[status]",$status[2], $file_content);
					}else{
						$file_content=str_replace("[status]",$status[0], $file_content);
					}
				}else{
					$file_content=str_replace("[status]",$status[1], $file_content);
				}
				$file_content=str_replace("[checked]","", $file_content);
				$file_content=str_replace("[module_name]","front",$file_content);
				$moreContent.=$file_content;
			}

			//生成前台管理服务类
			$title="<a href='$layer_autocode/db_service.php?type=2' target='_blank'>服务管理类</a>";
			$moreContent.=str_replace("[title]",$title,$title_model);
			$file=self::$manage_service_file;
			$file_content=str_replace("[file]", self::$save_dir.$file, $model);
			$origin_file= Gc::$nav_root_path.Gc::$module_root.DS.$file;
			$file_content=str_replace("[origin_file]",$origin_file, $file_content);
			$file_content=str_replace("[relative_file]",$file, $file_content);
			$file_content_old=file_get_contents($origin_file);
			$file_content_new=file_get_contents(self::$save_dir.$file);
			if($file_content_old==$file_content_new){
				$file_content=str_replace("[status]",$status[2], $file_content);
			}else{
				$file_content=str_replace("[status]","<font color='green'>[新增加]</font>", $file_content);
			}
			$file_content=str_replace("[checked]","", $file_content);
			$file_content=str_replace("[module_name]","front",$file_content);
			$moreContent.=$file_content;

			// 生成前端Action，继承基本Action
			if(self::$action_front_files&&(count(self::$action_front_files)>0)){
				$title="<a href='$layer_autocode/db_action.php' target='_blank'>控制器</a>";
				$moreContent.=str_replace("[title]",$title,$title_model);
			}
			foreach (self::$action_front_files as $file) {
				$file_content=str_replace("[file]", self::$save_dir.$file, $model);
				$origin_file= Gc::$nav_root_path.Gc::$module_root.DS.$file;
				$file_content=str_replace("[origin_file]",$origin_file, $file_content);
				$file_content=str_replace("[relative_file]",$file, $file_content);
				if(file_exists($origin_file)){
					$file_content_old=file_get_contents($origin_file);
					$file_content_new=file_get_contents(self::$save_dir.$file);
					if($file_content_old==$file_content_new){
						$file_content=str_replace("[status]",$status[2], $file_content);
					}else{
						$file_content=str_replace("[status]",$status[0], $file_content);
					}
				}else{
					$file_content=str_replace("[status]",$status[1], $file_content);
				}
				$file_content=str_replace("[checked]","", $file_content);
				$file_content=str_replace("[module_name]","front",$file_content);
				$moreContent.=$file_content;
			}

			// 生成前台所需的表示层页面
			if(self::$view_front_files&&(count(self::$view_front_files)>0)){
				$title="<a href='$layer_autocode/view/db_view_default.php' target='_blank'>表示层页面</a>";
				$moreContent.=str_replace("[title]",$title,$title_model);
			}
			foreach (self::$view_front_files as $file) {
				$file_content=str_replace("[file]", self::$save_dir.$file, $model);
				$origin_file= Gc::$nav_root_path.Gc::$module_root.DS.$file;
				$file_content=str_replace("[origin_file]",$origin_file, $file_content);
				$file_content=str_replace("[relative_file]",$file, $file_content);
				if(file_exists($origin_file)){
					$file_content_old=file_get_contents($origin_file);
					$file_content_new=file_get_contents(self::$save_dir.$file);
					if($file_content_old==$file_content_new){
						$file_content=str_replace("[status]",$status[2], $file_content);
					}else{
						$file_content=str_replace("[status]",$status[0], $file_content);
					}
				}else{
					$file_content=str_replace("[status]",$status[1], $file_content);
				}
				$file_content=str_replace("[checked]","", $file_content);
				$file_content=str_replace("[module_name]","front",$file_content);
				$moreContent.=$file_content;
			}
		}

		$save_dir=self::$save_dir;
		if(is_array($table_names))$table_names=implode(",", $table_names);
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
function toggledomain(source)
{
	var checkbox = document.getElementById('selectdomain');
	checkbox.checked = source.checked;

	var checkboxes = document.getElementsByName('overwritedomain[]');
	for(var i=0, n=checkboxes.length;i<n;i++) {
		checkboxes[i].checked = source.checked;
	}
}

function togglebg(source)
{
	var checkbox = document.getElementById('selectbg');
	checkbox.checked = source.checked;

	var checkboxes = document.getElementsByName('overwritebg[]');
	for(var i=0, n=checkboxes.length;i<n;i++) {
		checkboxes[i].checked = source.checked;
	}
}

function togglefront(source)
{
	var checkbox = document.getElementById('selectfront');
	checkbox.checked = source.checked;

	var checkboxes = document.getElementsByName('overwritefront[]');
	for(var i=0, n=checkboxes.length;i<n;i++) {
		checkboxes[i].checked = source.checked;
	}
}

function togglemodel(source)
{
	var checkbox = document.getElementById('selectmodel');
	if(checkbox)checkbox.checked = source.checked;

	var checkboxes = document.getElementsByName('overwritemodel[]');
	if(checkboxes){
		for(var i=0, n=checkboxes.length;i<n;i++) {
			checkboxes[i].checked = source.checked;
		}
	}
}

function toggle(source)
{
	toggledomain(source);
	togglebg(source);
	togglefront(source);
	togglemodel(source);
}
</script>

<div align="center">
<form><input type="hidden" name="model_save_dir" value="$save_dir" /><input type="hidden" name="table_names" value="$table_names" />
<table class="preview">
  <tbody>
	<tr>
		<th class="confirm">全&nbsp;&nbsp;选<input type="checkbox" id="overwrite" name="selectAll" onclick="toggle(this)"></th>
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