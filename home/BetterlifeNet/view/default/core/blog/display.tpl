{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
	<div class="contentBox">
		<b><my:a href="{$url_base}index.php?go=BetterlifeNet.auth.logout">退出</my:a></b><br/><br/>
		<b>共计{$countBlogs} 篇博客</b>
		{if $blogs}
		{foreach item=blog from=$blogs}
		<div id='blog{$blog.ID}' class="block">
			<b><my:a href='{$url_base}index.php?go=BetterlifeNet.comment.comment&blog_id={$blog.ID}&pageNo={$smarty.get.pageNo|default:"1"}'>{$blog.Blog_Name}</my:a>
			{if $blog.canEdit}[<my:a href="{$url_base}index.php?go=BetterlifeNet.blog.write&blog_id={$blog.ID}&pageNo={$smarty.get.pageNo|default:"1"}">改</my:a>]{/if}
			{if $blog.canDelete}[<my:a href="{$url_base}index.php?go=BetterlifeNet.blog.delete&blog_id={$blog.ID}&pageNo={$smarty.get.pageNo|default:"1"}">删</my:a>]{/if}
			</b><br/>
			{$blog.Blog_Content|nl2br}<br/><br/>
			由 {$blog.user.Username} 在 {$blog.CommitTime|date_format:'%Y-%m-%d %H:%M'} 发表<br/>
			评论数:{$viewObject->count_comments($blog.ID)}<br/>
		</div>
		{/foreach}<br/>
		<my:page src='{$url_base}index.php?go=BetterlifeNet.blog.display' /><br/>
		<b><my:a href='{$url_base}index.php?go=BetterlifeNet.blog.write&pageNo={$smarty.get.pageNo|default:"1"}'>新建博客</my:a></b><br/>
		{else}
		无博客，您是第一位!
		{/if}
	</div>
{/block}