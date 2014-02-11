{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
	<div><h1>评论列表(共计{$countComments}个)</h1></div>
	<table class="viewdoblock">
		<tr class="entry">
            <th class="header">标识</th>
            <th class="header">评论者标识</th>
            <th class="header">评论</th>
            <th class="header">博客标识</th>
			<th class="header">操作</th>
		</tr>
		{foreach item=comment from=$comments}
		<tr class="entry">
            <td class="content">{$comment.comment_id}</td>
            <td class="content">{$comment.user_id}</td>
            <td class="content">{$comment.comment}</td>
            <td class="content">{$comment.blog_id}</td>
			<td class="btnCol"><my:a href="{$url_base}index.php?go=model.comment.view&id={$comment.id}&pageNo={$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.comment.edit&id={$comment.id}&pageNo={$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{$url_base}index.php?go=model.comment.delete&id={$comment.id}&pageNo={$smarty.get.pageNo|default:"1"}">删除</my:a></td>
		</tr>
		{/foreach}
	</table>
	&nbsp;&nbsp;<my:page src='{$url_base}index.php?go=model.comment.lists' /><br/>
	<div align="center"><my:a href='{$url_base}index.php?go=model.comment.edit&pageNo={$smarty.get.pageNo|default:"1"}'>新建</my:a>|<my:a href='{$url_base}index.php?go=model.index.index'>返回首页</my:a></div>
</div>
{/block}