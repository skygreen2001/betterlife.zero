{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
	<div><h1>地区列表(共计{$countRegions}个)</h1></div>
	<table class="viewdoblock">
		<tr class="entry">
            <th class="header">标识</th>
            <th class="header">父地区标识</th>
            <th class="header">地区名称</th>
            <th class="header">地区类型</th>
			<th class="header">操作</th>
		</tr>
		{foreach item=region from=$regions}
		<tr class="entry">
            <td class="content">{$region.region_id}</td>
            <td class="content">{$region.parent_id}</td>
            <td class="content">{$region.region_name}</td>
            <td class="content">{$region.region_type}</td>
			<td class="btnCol"><my:a href="{$url_base}index.php?go=model.region.view&id={$region.id}&pageNo={$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.region.edit&id={$region.id}&pageNo={$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{$url_base}index.php?go=model.region.delete&id={$region.id}&pageNo={$smarty.get.pageNo|default:"1"}">删除</my:a></td>
		</tr>
		{/foreach}
	</table>
	&nbsp;&nbsp;<my:page src='{$url_base}index.php?go=model.region.lists' /><br/>
	<div align="center"><my:a href='{$url_base}index.php?go=model.region.edit&pageNo={$smarty.get.pageNo|default:"1"}'>新建</my:a>|<my:a href='{$url_base}index.php?go=model.index.index'>返回首页</my:a></div>
</div>
{/block}