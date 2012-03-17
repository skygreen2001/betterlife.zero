{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 <div class="block">  
	<div><h1>编辑博客</h1></div>
	<form name="blogForm" method="post"><input type="hidden" name="blog_id" value="{$blog.blog_id}"/>           
	<table class="viewdoblock">                                                                                                                 
        <tr class="entry"><td class="head">用户标识</th><td class="content"><input type="text" class="edit" name="user_id" value="{$blog.user_id}"/></td></tr>
        <tr class="entry"><td class="head">博客名称</th><td class="content"><input type="text" class="edit" name="blog_name" value="{$blog.blog_name}"/></td></tr>
        <tr class="entry"><td class="head">博客内容</th><td class="content"><input type="text" class="edit" name="content" value="{$blog.content}"/></td></tr>       
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>                                                            
	<div align="center"><my:a href='{$url_base}index.php?go=model.blog.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.blog.view&id={$blog.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看博客</my:a></div>    
</div>
{/block}