{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 <div class="block">
	<div><h1>{if $region}编辑{else}新增{/if}地区</h1><p><font color="red">{$message|default:''}</font></p></div>
	<form name="regionForm" method="post"><input type="hidden" name="region_id" value="{$region.region_id}"/>
	<table class="viewdoblock">
		{if $region}<tr class="entry"><th class="head">标识</th><td class="content">{$region.region_id}</td></tr>{/if}
		<tr class="entry"><th class="head">父地区标识</th><td class="content"><input type="text" class="edit" name="parent_id" value="{$region.parent_id}"/></td></tr>
		<tr class="entry"><th class="head">地区名称</th><td class="content"><input type="text" class="edit" name="region_name" value="{$region.region_name}"/></td></tr>
		<tr class="entry"><th class="head">地区类型</th><td class="content"><input type="text" class="edit" name="region_type" value="{$region.region_type}"/></td></tr>
		<tr class="entry"><th class="head">目录层级</th><td class="content"><input type="text" class="edit" name="level" value="{$region.level}"/></td></tr>
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>
	<div align="center"><my:a href='{$url_base}index.php?go=model.region.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>{if $region}|<my:a href='{$url_base}index.php?go=model.region.view&id={$region.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看地区</my:a>{/if}</div>
</div>
{/block}