{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
	<div class="contentBox">
		<b><my:a href="{$url_base}index.php?go=betterlife.auth.logout">退出</my:a></b><br/><br/>
		<b>共计{$countBlogs} 篇博客</b>
		{if $blogs}
		{foreach item=blog from=$blogs}
		<div id='blog{$blog.blog_id}' class="block">
			<b><my:a href='{$url_base}index.php?go=betterlife.comment.comment&blog_id={$blog.blog_id}&pageNo={$smarty.get.pageNo|default:"1"}'>{$blog.blog_name}</my:a>
			{if $blog.canEdit}[<my:a href="{$url_base}index.php?go=betterlife.blog.write&blog_id={$blog.blog_id}&pageNo={$smarty.get.pageNo|default:"1"}">改</my:a>]{/if}
			{if $blog.canDelete}[<my:a href="{$url_base}index.php?go=betterlife.blog.delete&blog_id={$blog.blog_id}&pageNo={$smarty.get.pageNo|default:"1"}">删</my:a>]{/if}
			</b><br/>
			{$blog.blog_content|nl2br}<br/><br/>
			由 {$blog.user.username} 在 {$blog.commitTime|date_format:'%Y-%m-%d %H:%M'} 发表<br/>
			评论数:{$viewObject->count_comments($blog.blog_id)}<br/>
		</div>
		{/foreach}<br/>
		<my:page src='{$url_base}index.php?go=betterlife.blog.display' /><br/>
		<b><my:a href='{$url_base}index.php?go=betterlife.blog.write&pageNo={$smarty.get.pageNo|default:"1"}'>新建博客</my:a></b><br/>
		{else}
		无博客，您是第一位!
		{/if}
	</div>
{/block}