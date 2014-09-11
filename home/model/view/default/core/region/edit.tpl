{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 <div class="block">
	<div><h1>编辑地区</h1><p><font color="red">{$message|default:''}</font></p></div>
	<form name="regionForm" method="post"><input type="hidden" name="ID" value="{$region.ID}"/>
	<table class="viewdoblock">
        <tr class="entry"><th class="head">父地区标识</th><td class="content"><input type="text" class="edit" name="Parent_ID" value="{$region.Parent_ID}"/></td></tr>
        <tr class="entry"><th class="head">地区名称</th><td class="content"><input type="text" class="edit" name="Region_Name" value="{$region.Region_Name}"/></td></tr>
        <tr class="entry"><th class="head">地区类型</th><td class="content"><input type="text" class="edit" name="Region_Type" value="{$region.Region_Type}"/></td></tr>
        <tr class="entry"><th class="head">目录层级</th><td class="content"><input type="text" class="edit" name="Level" value="{$region.Level}"/></td></tr>
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>
	<div align="center"><my:a href='{$url_base}index.php?go=model.region.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.region.view&id={$region.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看地区</my:a></div>
</div>
{/block}