<?php
/**
 +---------------------------------<br/>
 * 工具类:自动生成代码-前端默认的表示层
 +---------------------------------<br/>
 * @category betterlife
 * @package core.autoCode.view
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCodeViewDefault extends AutoCode
{
	/**
	 * 表示层生成定义的方式<br/>
	 * 0.生成前台所需的表示层页面。<br/>
	 * 1.生成标准的增删改查模板所需的表示层页面。<br/>
	 */
	public static $type;
	/**
	 * 表示层所在的目录
	 */
	public static $view_core;
	/**
	 * 表示层完整的保存路径
	 */
	public static $view_dir_full;
	/**
	 * View生成tpl所在的应用名称，默认同网站应用的名称
	 */
	public static $appName;
	/**
	 * 设置必需的路径
	 */
	public static function pathset()
	{
		switch (self::$type) {
		   case 0:
			 self::$app_dir=Gc::$appName;
			 if (empty(self::$appName)){
				self::$appName=Gc::$appName;
			 }
			 break;
		   case 1:
			 self::$app_dir="model";
			 self::$appName="model";
			 break;
		}
		self::$view_dir_full=self::$save_dir.self::$app_dir.DIRECTORY_SEPARATOR.Config_F::VIEW_VIEW.DIRECTORY_SEPARATOR.Gc::$self_theme_dir.DIRECTORY_SEPARATOR.Config_F::VIEW_CORE.DIRECTORY_SEPARATOR;

	}

	/**
	 * 自动生成代码-前端默认的表示层
	 */
	public static function AutoCode()
	{
		self::pathset();
		self::init();
		if (self::$isNoOutputCss) echo UtilCss::form_css()."\r\n";
		switch (self::$type) {
		   case 0:
				AutoCodeFoldHelper::foldEffectCommon("Content_41");
				echo "<font color='#FF0000'>生成前台所需的表示层页面:</font></a>";
				echo '<div id="Content_41" style="display:none;">';
				self::createModelIndexFile();
				self::createFrontModelPages();
				echo "</div><br>";
			 break;
		   case 1:
				AutoCodeFoldHelper::foldEffectCommon("Content_42");
				echo "<font color='#FF0000'>生成标准的增删改查模板表示层页面:</font></a>";
				echo '<div id="Content_42" style="display:none;">';
				self::createModelIndexFile();
				foreach (self::$fieldInfos as $tablename=>$fieldInfo){
					$tpl_listsContent=self::tpl_lists($tablename,$fieldInfo);
					$filename="lists".Config_F::SUFFIX_FILE_TPL;
					$tplName=self::saveTplDefineToDir($tablename,$tpl_listsContent,$filename);
					echo "生成导出完成:$tablename=>$tplName!<br/>";
					$tpl_viewContent=self::tpl_view($tablename,$fieldInfo);
					$filename="view".Config_F::SUFFIX_FILE_TPL;
					$tplName=self::saveTplDefineToDir($tablename,$tpl_viewContent,$filename);
					echo "生成导出完成:$tablename=>$tplName!<br/>";
					$tpl_editContent=self::tpl_edit($tablename,$fieldInfo);
					$filename="edit".Config_F::SUFFIX_FILE_TPL;
					$tplName=self::saveTplDefineToDir($tablename,$tpl_editContent,$filename);
					echo "生成导出完成:$tablename=>$tplName!<br/>";
				}
				echo "</div><br>";
			 break;
		}
	}

	/**
	 * 用户输入需求
	 */
	public static function UserInput($title=null,$inputArr=null)
	{
		$inputArr=array(
			"0"=>"生成前台所需的表示层页面。",
			"1"=>"生成标准的增删改查模板所需的表示层页面。"
		);
		return parent::UserInput("默认生成前台所需的表示层页面[用于前台]的输出文件路径参数",$inputArr);
	}

	/**
	 * 将表列定义转换成表示层列表页面tpl文件定义的内容
	 * @param string $tablename 表名
	 * @param array $fieldInfo 表列信息列表
	 */
	private static function tpl_lists($tablename,$fieldInfo)
	{
		$table_comment=self::tableCommentKey($tablename);
		$appname=self::$appName;
		$classname=self::getClassname($tablename);
		$instancename=self::getInstancename($tablename);
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
		$headers="";
		$contents="";
		foreach ($fieldNameAndComments as $key=>$value) {
			if (self::isNotColumnKeywork($key)){
				$isImage =self::columnIsImage($key,$value);
				if ($isImage)continue;
				$headers.="            <th class=\"header\">$value</th>\r\n";
				$contents.="            <td class=\"content\">{\${$instancename}.$key}</td>\r\n";
			}
		}
		if (!empty($headers)&&(strlen($headers)>2)){
			$headers=substr($headers,0,strlen($headers)-2);
			$contents=substr($contents,0,strlen($contents)-2);
		}
		$result = <<<LISTS
<div class="block">
	<div><h1>{$table_comment}列表(共计{\$count{$classname}s}个)</h1></div>
	<table class="viewdoblock">
		<tr class="entry">
$headers
			<th class="header">操作</th>
		</tr>
		{foreach item={$instancename} from=\${$instancename}s}
		<tr class="entry">
$contents
			<td class="btnCol"><my:a href="{\$url_base}index.php?go={$appname}.{$instancename}.view&id={\${$instancename}.id}&pageNo={\$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{\$url_base}index.php?go={$appname}.{$instancename}.edit&id={\${$instancename}.id}&pageNo={\$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{\$url_base}index.php?go={$appname}.{$instancename}.delete&id={\${$instancename}.id}&pageNo={\$smarty.get.pageNo|default:"1"}">删除</my:a></td>
		</tr>
		{/foreach}
	</table>
	&nbsp;&nbsp;<my:page src='{\$url_base}index.php?go={$appname}.{$instancename}.lists' /><br/>
	<div align="center"><my:a href='{\$url_base}index.php?go={$appname}.{$instancename}.edit&pageNo={\$smarty.get.pageNo|default:"1"}'>新建</my:a>|<my:a href='{\$url_base}index.php?go={$appname}.index.index'>返回首页</my:a></div>
</div>
LISTS;
		$result=self::tableToViewTplDefine($result);
		return $result;
	}

	/**
	 * 将表列定义转换成表示层列表页面tpl文件定义的内容
	 * @param string $tablename 表名
	 * @param array $fieldInfo 表列信息列表
	 */
	private static function tpl_edit($tablename,$fieldInfo)
	{
		$table_comment=self::tableCommentKey($tablename);
		$appname=self::$appName;
		$classname=self::getClassname($tablename);
		$instancename=self::getInstancename($tablename);
		$fieldNameAndComments=array();
		$text_area_fieldname=array();
		foreach ($fieldInfo as $fieldname=>$field)
		{
			$field_comment=$field["Comment"];
			if (contain($field_comment,"\r")||contain($field_comment,"\n"))
			{
				$field_comment=preg_split("/[\s,]+/", $field_comment);
				$field_comment=$field_comment[0];
			}
			if (self::columnIsTextArea($fieldname,$field["Type"]))
			{
				$text_area_fieldname[$fieldname]=$field_comment;
			}else{
				$fieldNameAndComments[$fieldname]=$field_comment;
			}
		}
		$headerscontents="";
		$idColumnName="id";
		$hasImgFormFlag="";
		foreach ($fieldNameAndComments as $key=>$value) {
			$idColumnName=DataObjectSpec::getRealIDColumnName($classname);
			if (self::isNotColumnKeywork($key)&&($idColumnName!=$key)){
				$isImage =self::columnIsImage($key,$value);
				if ($isImage){
					$hasImgFormFlag=" enctype=\"multipart/form-data\"";
					$headerscontents.="        <tr class=\"entry\"><th class=\"head\">$value</th><td class=\"content\"><input type=\"file\" class=\"edit\" name=\"{$key}Upload\" accept=\"image/png,image/gif,image/jpg,image/jpeg\" value=\"{\${$instancename}.$key}\"/></td></tr>\r\n";
				}else{
					$headerscontents.="        <tr class=\"entry\"><th class=\"head\">$value</th><td class=\"content\"><input type=\"text\" class=\"edit\" name=\"$key\" value=\"{\${$instancename}.$key}\"/></td></tr>\r\n";
				}
			}
		}

		if (count($text_area_fieldname)>=1){
			$kindEditor_prepare="    ";
			$ckeditor_prepare="    ";
			$xhEditor_prepare="    ";
			$ueEditor_prepare="";
			foreach ($text_area_fieldname as $key=>$value) {
				$headerscontents.="        <tr class=\"entry\"><th class=\"head\">$value</th><td class=\"content\">\r\n".
								  "        <textarea id=\"$key\" name=\"$key\" style=\"width:720px;height:300px;\">{\${$instancename}.$key}</textarea>\r\n".
								  "        </td></tr>\r\n";
				$kindEditor_prepare.="showHtmlEditor(\"$key\");";
				$ckeditor_prepare.="ckeditor_replace_$key();";
				$xhEditor_prepare.="pageInit_$key();";
				$ueEditor_prepare.="pageInit_ue_$key();";
			}

			$textareapreparesentence = <<<EDIT
 {if (\$online_editor=='KindEditor')}<script>
 $kindEditor_prepare</script>{/if}
 {if (\$online_editor=='CKEditor')}
 {\$editorHtml}
 <script>$(function(){
$ckeditor_prepare});</script>
 {/if}
 {if (\$online_editor=='xhEditor')}<script>\$(function(){
$xhEditor_prepare});</script>
 {/if}
EDIT;
			$ueTextareacontents=<<<UETC
    {if (\$online_editor=='UEditor')}
    <script>$ueEditor_prepare</script>
    {/if}
UETC;
		}
		if (!empty($headerscontents)&&(strlen($headerscontents)>2)){
			$headerscontents=substr($headerscontents,0,strlen($headerscontents)-2);
		}
		$result = <<<EDIT
 <div class="block">
	<div><h1>编辑{$table_comment}</h1><p><font color="red">{\$message|default:''}</font></p></div>
	<form name="{$instancename}Form" method="post"$hasImgFormFlag><input type="hidden" name="$idColumnName" value="{\${$instancename}.$idColumnName}"/>
	<table class="viewdoblock">
$headerscontents
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>
	<div align="center"><my:a href='{\$url_base}index.php?go=$appname.{$instancename}.lists&pageNo={\$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{\$url_base}index.php?go=$appname.{$instancename}.view&id={\${$instancename}.id}&pageNo={\$smarty.get.pageNo|default:"1"}'>查看{$table_comment}</my:a></div>
</div>$ueTextareacontents
EDIT;
		if (count($text_area_fieldname)>=1){
			$result=$textareapreparesentence."\r\n".$result;
		}
		$result=self::tableToViewTplDefine($result);
		return $result;
	}

	/**
	 * 将表列定义转换成表示层列表页面tpl文件定义的内容
	 * @param string $tablename 表名
	 * @param array $fieldInfo 表列信息列表
	 */
	private static function tpl_view($tablename,$fieldInfo)
	{
		$table_comment=self::tableCommentKey($tablename);
		$appname=self::$appName;
		$classname=self::getClassname($tablename);
		$instancename=self::getInstancename($tablename);
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
		$headerscontents="";
		foreach ($fieldNameAndComments as $key=>$value) {
			if (self::isNotColumnKeywork($key)){
				$isImage =self::columnIsImage($key,$value);
				if ($isImage){
					$headerscontents.="        <tr class=\"entry\"><th class=\"head\">$value</th><td class=\"content\">\r\n".
					"            <div class=\"wrap_2_inner\"><img src=\"{\$uploadImg_url|cat:\$$instancename.$key}\" alt=\"$value\"></div>\r\n".
            		"            <br/>存储相对路径:{\$$instancename.$key}</td></tr>\r\n";
				}else{
					$headerscontents.="        <tr class=\"entry\"><th class=\"head\">$value</th><td class=\"content\">{\$$instancename.$key}</td></tr> \r\n";
				}
			}
		}
		if (!empty($headerscontents)&&(strlen($headerscontents)>2)){
			$headerscontents=substr($headerscontents,0,strlen($headerscontents)-2);
		}
		$result = <<<VIEW
<div class="block">
	<div><h1>查看{$table_comment}</h1></div>
	<table class="viewdoblock">
$headerscontents
	</table>
	<div align="center"><my:a href='{\$url_base}index.php?go=$appname.{$instancename}.lists&pageNo={\$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{\$url_base}index.php?go=$appname.{$instancename}.edit&id={\${$instancename}.id}&pageNo={\$smarty.get.pageNo|default:"1"}'>修改{$table_comment}</my:a></div>
</div>
VIEW;
		$result=self::tableToViewTplDefine($result);
		return $result;
	}

	/**
	 * 将表列定义转换成表示层tpl文件定义的内容
	 * @param string $contents 页面内容
	 */
	private static function tableToViewTplDefine($contents)
	{
		$result="{extends file=\"\$templateDir/layout/normal/layout.tpl\"}\r\n".
				"{block name=body}\r\n".
				"$contents\r\n".
				"{/block}";
		return $result;
	}

	/**
	 * 生成标准的增删改查模板Action文件需生成首页访问所有生成的链接
	 */
	private static function createModelIndexFile()
	{
		$tpl_content="    <div><h1>这是首页列表(共计数据对象".count(self::$tableInfoList)."个)</h1></div>\r\n";
		$result="";
		$appname=self::$appName;
		if (self::$tableInfoList!=null&&count(self::$tableInfoList)>0){
			foreach (self::$tableInfoList as $tablename=>$tableInfo){
				$table_comment=self::$tableInfoList[$tablename]["Comment"];
				if (contain($table_comment,"\r")||contain($table_comment,"\n")){
					$table_comment=preg_split("/[\s,]+/", $table_comment);
					$table_comment=$table_comment[0];
				}
				$instancename=self::getInstancename($tablename);
				$result.="        <tr class=\"entry\"><td class=\"content\"><a href=\"{\$url_base}index.php?go={$appname}.{$instancename}.lists\">{$table_comment}</a></td></tr>\r\n";
			}
		}
		$tpl_content.="    <table class=\"viewdoblock\" style=\"width: 500px;\">\r\n".
					 $result.
					 "    </table>\r\n".
					 "        \r\n";
		$tpl_content=self::tableToViewTplDefine($tpl_content);
		$filename="index".Config_F::SUFFIX_FILE_TPL;
		$dir=self::$view_dir_full."index".DIRECTORY_SEPARATOR;
		return self::saveDefineToDir($dir,$filename,$tpl_content);
	}

	/**
	 * 生成前台所需的表示层页面
	 */
	private static function createFrontModelPages()
	{
		foreach (self::$fieldInfos as $tablename=>$fieldInfo){
			if(self::$type==0) {
				$classname=self::getClassname($tablename);
				if ($classname=="Admin")continue;
			}
			$table_comment=self::tableCommentKey($tablename);
			$appname=self::$appName;
			$instancename=self::getInstancename($tablename);
			$link="    <div align=\"center\"><my:a href=\"{\$url_base}index.php?go={$appname}.{$instancename}.view\">查看</my:a>|<my:a href=\"{\$url_base}index.php?go={$appname}.{$instancename}.edit\">修改</my:a>";
			$back_index="    <my:a href='{\$url_base}index.php?go={$appname}.index.index'>返回首页</my:a></div>";
			$tpl_content=self::tableToViewTplDefine("    <div><h1>".$table_comment."列表</h1></div><br/>\r\n{$link}<br/>\r\n{$back_index}");
			$filename="lists".Config_F::SUFFIX_FILE_TPL;
			$tplName=self::saveTplDefineToDir($tablename,$tpl_content,$filename);
			echo "生成导出完成:$tablename=>$tplName!<br/>";
			$link="     <div align=\"center\"><my:a href=\"{\$url_base}index.php?go={$appname}.{$instancename}.lists\">返回列表</my:a>";
			$tpl_content=self::tableToViewTplDefine("    <div><h1>查看".$table_comment."</h1></div><br/>\r\n{$link}<br/>\r\n{$back_index}");
			$filename="view".Config_F::SUFFIX_FILE_TPL;
			$tplName=self::saveTplDefineToDir($tablename,$tpl_content,$filename);
			echo "生成导出完成:$tablename=>$tplName!<br/>";
			$tpl_content=self::tableToViewTplDefine("    <div><h1>编辑".$table_comment."</h1></div><br/>\r\n{$link}<br/>\r\n{$back_index}");
			$filename="edit".Config_F::SUFFIX_FILE_TPL;
			$tplName=self::saveTplDefineToDir($tablename,$tpl_content,$filename);
			echo "生成导出完成:$tablename=>$tplName!<br/>";
		}
	}

	/**
	 * 保存生成的tpl代码到指定命名规范的文件中
	 * @param string $tablename 表名称
	 * @param string $defineTplFileContent 生成的代码
	 * @param string $filename 文件名称
	 */
	private static function saveTplDefineToDir($tablename,$defineTplFileContent,$filename)
	{
		$package =self::getInstancename($tablename);
		$dir=self::$view_dir_full.$package.DIRECTORY_SEPARATOR;
		return self::saveDefineToDir($dir,$filename,$defineTplFileContent);
	}
}

?>