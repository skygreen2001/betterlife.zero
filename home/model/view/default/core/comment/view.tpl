{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
	<div><h1>查看评论</h1></div>
	<table class="viewdoblock">
        <tr class="entry"><th class="head">标识</th><td class="content">{$comment.ID}</td></tr> 
        <tr class="entry"><th class="head">评论者标识</th><td class="content">{$comment.User_ID}</td></tr> 
        <tr class="entry"><th class="head">评论</th><td class="content">{$comment.Comment}</td></tr> 
        <tr class="entry"><th class="head">博客标识</th><td class="content">{$comment.Blog_ID}</td></tr> 
	</table>
	<div align="center"><my:a href='{$url_base}index.php?go=model.comment.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.comment.edit&id={$comment.id}&pageNo={$smarty.get.pageNo|default:"1"}'>修改评论</my:a></div>
</div>
{/block}