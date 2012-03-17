{* Flexy 语法写法
<b><my:a href="{url_base}index.php?g=betterlife&m=auth&a=logout">退出</my:a></b><br/><br/>
<b>共计{countBlogs} 篇博客</b>
{if:blogs}
{foreach:blogs,blog}         
<div id='blog{blog.id}' style="padding:10px;margin-top:10px;border:1px solid #cfcfcf;">  
<b><my:a href='{url_base}index.php?g=betterlife&m=blog&a=post&id={blog.id}&pageNo={_GET[pageNo]}'>{blog.name}</my:a>[<my:a href="{url_base}index.php?g=betterlife&m=blog&a=write&id={blog.id}&pageNo={_GET[pageNo]}">改</my:a>][<my:a href="{url_base}index.php?g=betterlife&m=blog&a=delete&id={blog.id}&pageNo={_GET[pageNo]}">删</my:a>]</b><br/>
{blog.content}<br/><br/>  
由 {blog.user.name} 在 {blog.commitTimeShow} 发表<br/>
评论数:{count_comments(blog.id)}<br/>
</div>
{end:}<br/>       
<my:page src='{url_base}index.php?go=betterlife.blog.display' /><br/>
<b><my:a href='{url_base}index.php?go=betterlife.blog.write&pageNo={_GET[pageNo]}'>新建博客</my:a></b><br/>
{else:}
无博客，你是第一位!
{end:}  
 <!--Smarty 模板的写法--> 
 *}
{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body} 
	<div>
		<b><my:a href="{$url_base}index.php?go=betterlife.auth.logout">退出</my:a></b><br/><br/>
		<b>共计{$countBlogs} 篇博客</b>
		{if $blogs}
		{foreach item=blog from=$blogs}         
		<div id='blog{$blog.id}' class="block"> 
			<b><my:a href='{$url_base}index.php?go=betterlife.blog.post&id={$blog.id}&pageNo={$smarty.get.pageNo|default:"1"}'>{$blog.name}</my:a>
			{if $blog.canEdit}[<my:a href="{$url_base}index.php?go=betterlife.blog.write&id={$blog.id}&pageNo={$smarty.get.pageNo|default:"1"}">改</my:a>]{/if}
			{if $blog.canDelete}[<my:a href="{$url_base}index.php?go=betterlife.blog.delete&id={$blog.id}&pageNo={$smarty.get.pageNo|default:"1"}">删</my:a>]{/if}
			</b><br/>
			{$blog.content|nl2br}<br/><br/>
			由 {$blog.user.name} 在 {$blog.commitTimeShow} 发表<br/>
			评论数:{$viewObject->count_comments($blog.id)}<br/>
		</div>
		{/foreach}<br/>       
		<my:page src='{$url_base}index.php?go=betterlife.blog.display' /><br/>
		<b><my:a href='{$url_base}index.php?go=betterlife.blog.write&pageNo={$smarty.get.pageNo|default:"1"}'>新建博客</my:a></b><br/>
		{else}              
		无博客，您是第一位!
		{/if} 
	</div>
{/block}