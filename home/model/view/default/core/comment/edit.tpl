{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 <div class="block">  
	<div><h1>编辑评论</h1></div>
	<form name="commentForm" method="post"><input type="hidden" name="id" value="{$comment.id}"/>           
	<table class="viewdoblock">                                                                                                                 
        <tr class="entry"><td class="head">评论者编号</th><td class="content"><input type="text" class="edit" name="userId" value="{$comment.userId}"/></td></tr>
        <tr class="entry"><td class="head">评论</th><td class="content"><input type="text" class="edit" name="comment" value="{$comment.comment}"/></td></tr>
        <tr class="entry"><td class="head">博客编号</th><td class="content"><input type="text" class="edit" name="blogId" value="{$comment.blogId}"/></td></tr>       
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>                                                            
	<div align="center"><my:a href='{$url_base}index.php?go=model.comment.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.comment.view&id={$comment.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看评论</my:a></div>    
</div>
{/block}