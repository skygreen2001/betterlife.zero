{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">  
	<div><h1>查看博客</h1></div>     
	<table class="viewdoblock">   
        <tr class="entry"><td class="head">用户编号</th><td class="content">{$blog.userId}</td></tr> 
        <tr class="entry"><td class="head">博客名称</th><td class="content">{$blog.name}</td></tr> 
        <tr class="entry"><td class="head">博客内容</th><td class="content">{$blog.content}</td></tr>          
	</table>                                                            
	<div align="center"><my:a href='index.php?go=model.blog.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='index.php?go=model.blog.edit&id={$blog.id}&pageNo={$smarty.get.pageNo|default:"1"}'>修改博客</my:a></div>    
</div>
{/block}