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
	/**
	 * 应用:后台名称
	 */
	public static $m_bg="admin";
	/**
	 * 应用:模板名称
	 */
	public static $m_model="model";

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
		self::$manage_service_file=Gc::$appName.DIRECTORY_SEPARATOR.self::$dir_src.DIRECTORY_SEPARATOR.AutoCodeService::$service_dir.DIRECTORY_SEPARATOR."Manager_Service.php";
		$category_cap=Gc::$appName;
		$category_cap{0}=ucfirst($category_cap{0});
		self::$bg_action_index_file=self::$m_bg.DIRECTORY_SEPARATOR.AutoCodeAction::$action_dir.DIRECTORY_SEPARATOR."Action_".$category_cap.".php";
		self::$bg_action_upload_file=self::$m_bg.DIRECTORY_SEPARATOR.AutoCodeAction::$action_dir.DIRECTORY_SEPARATOR."Action_Upload.php";
		self::$bg_manage_service_ext_file=self::$m_bg.DIRECTORY_SEPARATOR.self::$dir_src.DIRECTORY_SEPARATOR.AutoCodeService::$service_dir.DIRECTORY_SEPARATOR.AutoCodeService::$ext_dir.DIRECTORY_SEPARATOR."Manager_ExtService.php";
		self::$bg_service_xml_file=self::$m_bg.DIRECTORY_SEPARATOR.self::$dir_src.DIRECTORY_SEPARATOR.AutoCodeService::$service_dir.DIRECTORY_SEPARATOR."service.config.xml";
		self::$bg_menu_xml_file=self::$m_bg.DIRECTORY_SEPARATOR.self::$dir_src.DIRECTORY_SEPARATOR."view".DIRECTORY_SEPARATOR."menu".DIRECTORY_SEPARATOR."menu.config.xml";
		self::$model_index_file=self::$m_model.DIRECTORY_SEPARATOR.Config_F::VIEW_VIEW.DIRECTORY_SEPARATOR.Gc::$self_theme_dir.DIRECTORY_SEPARATOR.Config_F::VIEW_CORE.DIRECTORY_SEPARATOR."index".DIRECTORY_SEPARATOR."index".Config_F::SUFFIX_FILE_TPL;
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
		$file ="C:\wamp\www\betterlife\model\betterlife\src\domain\core\Blog.php";
		$origin_file="C:\wamp\www\betterlife\home\betterlife\src\domain\core\Blog.php";
		$url_base=Gc::$url_base;
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

		$title="数据模型<Domain|Model>";
		$moreContent=str_replace("[title]",$title,$module_model);
		$moreContent=str_replace("[checked]","checked", $moreContent);
		$moreContent=str_replace("[module_name]","domain",$moreContent);

		$title="实体数据对象类";
		$moreContent.=str_replace("[title]",$title,$title_model);
		//[前台]生成实体数据对象
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
			$file_content=str_replace("[checked]","checked", $file_content);
			$file_content=str_replace("[module_name]","domain",$file_content);
			$moreContent.=$file_content;
		}
		//[前台]生成枚举类型
		if(self::$enum_files&&(count(self::$enum_files)>0)){
			$title="枚举类型类";
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
			$file_content=str_replace("[checked]","checked", $file_content);
			$file_content=str_replace("[module_name]","domain",$file_content);
			$moreContent.=$file_content;
		}

		$title="[后台]<使用ExtJs框架>";
		$moreContent.=str_replace("[title]",$title,$module_model);
		$moreContent=str_replace("[module_name]","bg",$moreContent);
		$moreContent=str_replace("[checked]","checked", $moreContent);
		//[后台]生成使用ExtJs框架的Service[后台]文件
		if(self::$service_bg_files&&(count(self::$service_bg_files)>0)){
			$title="服务层文件";
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
			$file_content=str_replace("[checked]","checked", $file_content);
			$file_content=str_replace("[module_name]","bg",$file_content);
			$moreContent.=$file_content;
		}

		//[后台]生成后台管理服务类
		$title="服务管理类";
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
		$file_content=str_replace("[checked]","checked", $file_content);
		$file_content=str_replace("[module_name]","bg",$file_content);
		$moreContent.=$file_content;

		//[后台]生成后台服务配置文件:service.config.xml
		$title="服务配置文件";
		$moreContent.=str_replace("[title]",$title,$title_model);
		$file=self::$bg_service_xml_file;
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
		$file_content=str_replace("[checked]","checked", $file_content);
		$file_content=str_replace("[module_name]","bg",$file_content);
		$moreContent.=$file_content;

		//[后台]控制器Action文件
		$title="控制器<Action文件>";
		$moreContent.=str_replace("[title]",$title,$title_model);
		//[后台]核心代码控制器
		$file=self::$bg_action_index_file;
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
		$file_content=str_replace("[checked]","checked", $file_content);
		$file_content=str_replace("[module_name]","bg",$file_content);
		$moreContent.=$file_content;

		//[后台]上传文件控制器
		$file=self::$bg_action_upload_file;
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
		$file_content=str_replace("[checked]","checked", $file_content);
		$file_content=str_replace("[module_name]","bg",$file_content);
		$moreContent.=$file_content;

		//[后台]生成后端tpl模板显示文件导出
		if(self::$view_bg_files&&(count(self::$view_bg_files)>0)){
			$title="显示层文件";
			$moreContent.=str_replace("[title]",$title,$title_model);
		}
		foreach (self::$view_bg_files as $file) {
			$file_content=str_replace("[file]", self::$save_dir.$file, $model);
			$origin_file= Gc::$nav_root_path.Gc::$module_root.DIRECTORY_SEPARATOR.$file;
			$file_content=str_replace("[origin_file]",$origin_file, $file_content);
			$file_content=str_replace("[relative_file]",$file, $file_content);
			if(file_exists($origin_file)){
				$file_content=str_replace("[status]",$status[0], $file_content);
			}else{
				$file_content=str_replace("[status]",$status[1], $file_content);
			}

			$file_content=str_replace("[checked]","checked", $file_content);
			$file_content=str_replace("[module_name]","bg",$file_content);
			$moreContent.=$file_content;
		}

		//[后台]采用ExtJs框架生成后端Js文件导出
		if(self::$bg_ext_js_files&&(count(self::$bg_ext_js_files)>0)){
			$title="Js文件<显示层核心>";
			$moreContent.=str_replace("[title]",$title,$title_model);
		}
		foreach (self::$bg_ext_js_files as $file) {
			$file_content=str_replace("[file]", self::$save_dir.$file, $model);
			$origin_file= Gc::$nav_root_path.Gc::$module_root.DIRECTORY_SEPARATOR.$file;
			$file_content=str_replace("[origin_file]",$origin_file, $file_content);
			$file_content=str_replace("[relative_file]",$file, $file_content);
			if(file_exists($origin_file)){
				$file_content=str_replace("[status]",$status[0], $file_content);
			}else{
				$file_content=str_replace("[status]",$status[1], $file_content);
			}
			$file_content=str_replace("[checked]","checked", $file_content);
			$file_content=str_replace("[module_name]","bg",$file_content);
			$moreContent.=$file_content;
		}

		//[后台]生成Ajax请求php文件:
		if(self::$bg_ajax_php_files&&(count(self::$bg_ajax_php_files)>0)){
			$title="Ajax请求php文件";
			$moreContent.=str_replace("[title]",$title,$title_model);
		}
		foreach (self::$bg_ajax_php_files as $file) {
			$file_content=str_replace("[file]", self::$save_dir.$file, $model);
			$origin_file= Gc::$nav_root_path.Gc::$module_root.DIRECTORY_SEPARATOR.$file;
			$file_content=str_replace("[origin_file]",$origin_file, $file_content);
			$file_content=str_replace("[relative_file]",$file, $file_content);
			if(file_exists($origin_file)){
				$file_content=str_replace("[status]",$status[0], $file_content);
			}else{
				$file_content=str_replace("[status]",$status[1], $file_content);
			}

			$file_content=str_replace("[checked]","checked", $file_content);
			$file_content=str_replace("[module_name]","bg",$file_content);
			$moreContent.=$file_content;
		}

		//[后台]在admin/src/view/menu目录下菜单配置文件:menu.config.xml里添加没有的代码
		$title="左侧菜单配置文件";
		$moreContent.=str_replace("[title]",$title,$title_model);
		$file=self::$bg_menu_xml_file;
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

		$file_content=str_replace("[checked]","checked", $file_content);
		$file_content=str_replace("[module_name]","bg",$file_content);
		$moreContent.=$file_content;

		$title="[前台]";
		$moreContent.=str_replace("[title]",$title,$module_model);
		$moreContent=str_replace("[module_name]","front",$moreContent);
		$moreContent=str_replace("[checked]","", $moreContent);
		//生成标准方法的Service文件
		if(self::$service_files&&(count(self::$service_files)>0)){
			$title="标准方法的服务层文件";
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
			$file_content=str_replace("[checked]","", $file_content);
			$file_content=str_replace("[module_name]","front",$file_content);
			$moreContent.=$file_content;
		}

		//生成前台管理服务类
		$title="服务管理类";
		$moreContent.=str_replace("[title]",$title,$title_model);
		$file=self::$manage_service_file;
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
		$file_content=str_replace("[checked]","", $file_content);
		$file_content=str_replace("[module_name]","front",$file_content);
		$moreContent.=$file_content;

		// 生成前端Action，继承基本Action
		if(self::$action_front_files&&(count(self::$action_front_files)>0)){
			$title="控制器";
			$moreContent.=str_replace("[title]",$title,$title_model);
		}
		foreach (self::$action_front_files as $file) {
			$file_content=str_replace("[file]", self::$save_dir.$file, $model);
			$origin_file= Gc::$nav_root_path.Gc::$module_root.DIRECTORY_SEPARATOR.$file;
			$file_content=str_replace("[origin_file]",$origin_file, $file_content);
			$file_content=str_replace("[relative_file]",$file, $file_content);
			if(file_exists($origin_file)){
				$file_content=str_replace("[status]",$status[0], $file_content);
			}else{
				$file_content=str_replace("[status]",$status[1], $file_content);
			}
			$file_content=str_replace("[checked]","", $file_content);
			$file_content=str_replace("[module_name]","front",$file_content);
			$moreContent.=$file_content;
		}

		// 生成前台所需的表示层页面
		if(self::$view_front_files&&(count(self::$view_front_files)>0)){
			$title="表示层页面";
			$moreContent.=str_replace("[title]",$title,$title_model);
		}
		foreach (self::$view_front_files as $file) {
			$file_content=str_replace("[file]", self::$save_dir.$file, $model);
			$origin_file= Gc::$nav_root_path.Gc::$module_root.DIRECTORY_SEPARATOR.$file;
			$file_content=str_replace("[origin_file]",$origin_file, $file_content);
			$file_content=str_replace("[relative_file]",$file, $file_content);
			if(file_exists($origin_file)){
				$file_content=str_replace("[status]",$status[0], $file_content);
			}else{
				$file_content=str_replace("[status]",$status[1], $file_content);
			}
			$file_content=str_replace("[checked]","", $file_content);
			$file_content=str_replace("[module_name]","front",$file_content);
			$moreContent.=$file_content;
		}

		$model_module=Gc::$nav_root_path.Gc::$module_root.DIRECTORY_SEPARATOR.self::$m_model.DIRECTORY_SEPARATOR;
		if(is_dir($model_module)){
			$title="[通用模板]";
			$moreContent.=str_replace("[title]",$title,$module_model);
			$moreContent=str_replace("[module_name]","model",$moreContent);
			$moreContent=str_replace("[checked]","", $moreContent);

			// 生成标准的增删改查模板Action，继承基本Action
			if(self::$action_model_files&&(count(self::$action_model_files)>0)){
				$title="控制器";
				$moreContent.=str_replace("[title]",$title,$title_model);
			}
			foreach (self::$action_model_files as $file) {
				$file_content=str_replace("[file]", self::$save_dir.$file, $model);
				$origin_file= Gc::$nav_root_path.Gc::$module_root.DIRECTORY_SEPARATOR.$file;
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
			$title="模板首页";
			$moreContent.=str_replace("[title]",$title,$title_model);
			$file=self::$model_index_file;
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
			$file_content=str_replace("[checked]","", $file_content);
			$file_content=str_replace("[module_name]","model",$file_content);
			$moreContent.=$file_content;

			// 生成标准的增删改查模板表示层页面
			if(self::$view_model_files&&(count(self::$view_model_files)>0)){
				$title="表示层页面";
				$moreContent.=str_replace("[title]",$title,$title_model);
			}
			foreach (self::$view_model_files as $file) {
				$file_content=str_replace("[file]", self::$save_dir.$file, $model);
				$origin_file= Gc::$nav_root_path.Gc::$module_root.DIRECTORY_SEPARATOR.$file;
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