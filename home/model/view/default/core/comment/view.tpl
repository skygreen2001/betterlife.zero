{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">  
	<div><h1>查看评论</h1></div>     
	<table class="viewdoblock">   
        <tr class="entry"><td class="head">评论者编号</th><td class="content">{$comment.userId}</td></tr> 
        <tr class="entry"><td class="head">评论</th><td class="content">{$comment.comment}</td></tr> 
        <tr class="entry"><td class="head">博客编号</th><td class="content">{$comment.blogId}</td></tr>          
	</table>                                                            
	<div align="center"><my:a href='index.php?go=model.comment.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='index.php?go=model.comment.edit&id={$comment.id}&pageNo={$smarty.get.pageNo|default:"1"}'>修改评论</my:a></div>    
</div>
{/block}