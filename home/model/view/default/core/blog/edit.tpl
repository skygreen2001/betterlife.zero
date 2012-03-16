{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 <div class="block">  
	<div><h1>编辑博客</h1></div>
	<form name="blogForm" method="post"><input type="hidden" name="id" value="{$blog.id}"/>           
	<table class="viewdoblock">                                                                                                                 
        <tr class="entry"><td class="head">用户编号</th><td class="content"><input type="text" class="edit" name="userId" value="{$blog.userId}"/></td></tr>
        <tr class="entry"><td class="head">博客名称</th><td class="content"><input type="text" class="edit" name="name" value="{$blog.name}"/></td></tr>
        <tr class="entry"><td class="head">博客内容</th><td class="content"><input type="text" class="edit" name="content" value="{$blog.content}"/></td></tr>       
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>                                                            
	<div align="center"><my:a href='{$url_base}index.php?go=model.blog.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.blog.view&id={$blog.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看博客</my:a></div>    
</div>
{/block}