{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
	<div><h1>通知列表(共计{$countNotices}个)</h1></div>
	<table class="viewdoblock">
		<tr class="entry">
			<th class="header">编号</th>
			<th class="header">通知分类</th>
			<th class="header">标题</th>
			<th class="header">通知内容</th>
			<th class="header">操作</th>
		</tr>
		{foreach item=notice from=$notices}
		<tr class="entry">
			<td class="content">{$notice.notice_id}</td>
			<td class="content">{$notice.noticeType}</td>
			<td class="content">{$notice.title}</td>
			<td class="content">{$notice.notice_content}</td>
			<td class="btnCol"><my:a href="{$url_base}index.php?go=model.notice.view&id={$notice.notice_id}&pageNo={$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.notice.edit&id={$notice.notice_id}&pageNo={$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{$url_base}index.php?go=model.notice.delete&id={$notice.notice_id}&pageNo={$smarty.get.pageNo|default:"1"}">删除</my:a></td>
		</tr>
		{/foreach}
	</table>
	&nbsp;&nbsp;<my:page src='{$url_base}index.php?go=model.notice.lists' /><br/>
	<div align="center"><my:a href='{$url_base}index.php?go=model.notice.edit&pageNo={$smarty.get.pageNo|default:"1"}'>新建</my:a>|<my:a href='{$url_base}index.php?go=model.index.index'>返回首页</my:a></div>
</div>
{/block}