{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
	<div><h1>用户日志列表(共计{$countLogusers}个)</h1></div>
	<table class="viewdoblock">
		<tr class="entry">
			<th class="header">标识</th>
			<th class="header">用户标识</th>
			<th class="header">类型</th>
			<th class="header">日志详情</th>
			<th class="header">操作</th>
		</tr>
		{foreach item=loguser from=$logusers}
		<tr class="entry">
			<td class="content">{$loguser.loguser_id}</td>
			<td class="content">{$loguser.user_id}</td>
			<td class="content">{$loguser.userType}</td>
			<td class="content">{$loguser.log_content}</td>
			<td class="btnCol"><my:a href="{$url_base}index.php?go=model.loguser.view&id={$loguser.loguser_id}&pageNo={$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.loguser.edit&id={$loguser.loguser_id}&pageNo={$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{$url_base}index.php?go=model.loguser.delete&id={$loguser.loguser_id}&pageNo={$smarty.get.pageNo|default:"1"}">删除</my:a></td>
		</tr>
		{/foreach}
	</table>
	&nbsp;&nbsp;<my:page src='{$url_base}index.php?go=model.loguser.lists' /><br/>
	<div align="center"><my:a href='{$url_base}index.php?go=model.loguser.edit&pageNo={$smarty.get.pageNo|default:"1"}'>新建</my:a>|<my:a href='{$url_base}index.php?go=model.index.index'>返回首页</my:a></div>
</div>
{/block}