{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
	<div><h1>功能信息列表(共计{$countFunctionss}个)</h1></div>
	<table class="viewdoblock">
		<tr class="entry">
			<th class="header">标识</th>
			<th class="header">允许访问的URL权限</th>
			<th class="header">操作</th>
		</tr>
		{foreach item=functions from=$functionss}
		<tr class="entry">
			<td class="content">{$functions.functions_id}</td>
			<td class="content">{$functions.url}</td>
			<td class="btnCol"><my:a href="{$url_base}index.php?go=model.functions.view&id={$functions.id}&pageNo={$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.functions.edit&id={$functions.id}&pageNo={$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{$url_base}index.php?go=model.functions.delete&id={$functions.id}&pageNo={$smarty.get.pageNo|default:"1"}">删除</my:a></td>
		</tr>
		{/foreach}
	</table>
	&nbsp;&nbsp;<my:page src='{$url_base}index.php?go=model.functions.lists' /><br/>
	<div align="center"><my:a href='{$url_base}index.php?go=model.functions.edit&pageNo={$smarty.get.pageNo|default:"1"}'>新建</my:a>|<my:a href='{$url_base}index.php?go=model.index.index'>返回首页</my:a></div>
</div>
{/block}