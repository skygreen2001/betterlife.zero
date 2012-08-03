{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">  
	<div><h1>博客列表(共计{$countBlogs}个)</h1></div>     
	<table class="viewdoblock">
		<tr class="entry">
            <th class="header">标识</th>
            <th class="header">用户标识</th>
            <th class="header">博客标题</th>
            <th class="header">博客内容</th>                                  
			<th class="header">操作</th>
		</tr>       
		{foreach item=blog from=$blogs}     
		<tr class="entry">                            
            <td class="content">{$blog.blog_id}</td>
            <td class="content">{$blog.user_id}</td>
            <td class="content">{$blog.blog_name}</td>
            <td class="content">{$blog.content}</td>
			<td class="btnCol"><my:a href="{$url_base}index.php?go=model.blog.view&id={$blog.id}&pageNo={$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.blog.edit&id={$blog.id}&pageNo={$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{$url_base}index.php?go=model.blog.delete&id={$blog.id}&pageNo={$smarty.get.pageNo|default:"1"}">删除</my:a></td>
		</tr> 
		{/foreach}                                                           
	</table> 
	&nbsp;&nbsp;<my:page src='{$url_base}index.php?go=model.blog.lists' /><br/>   
	<div align="center"><my:a href='{$url_base}index.php?go=model.blog.edit&pageNo={$smarty.get.pageNo|default:"1"}'>新建</my:a>|<my:a href='{$url_base}index.php?go=model.index.index'>返回首页</my:a></div>    
</div>
{/block}