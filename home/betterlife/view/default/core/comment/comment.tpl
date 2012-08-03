{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body} 
	{if ($online_editor=='KindEditor')}<script>showHtmlEditor("comment");</script>{/if}   
	{if ($online_editor=='CKEditor')}
	{$editorHtml}
	<script>$(function(){
		 ckeditor_replace_comment();});</script>
	 {/if}
	 {if ($online_editor=='xhEditor')}
	<script>$(function(){
		pageInit_comment();});</script>
	{/if}
	
	<div id="content" class="contentBox">
		<my:a href='{$url_base}?go=betterlife.auth.logout'><b>退出</b></my:a><br/>
		<my:a href='{$url_base}?go=betterlife.blog.display&pageNo={$smarty.get.pageNo|default:"1"}'><b>博客列表</b></my:a>
		<div id='blog{$blog.blog_id}' >
			<h1>{$blog.blog_name}</h1>
			<p>{$blog.content|nl2br}</p>
			评论数:{$blog.count_comments}
		</div>  
		{if !$smarty.get.comment_id}
			{foreach item=comment from=$blog.comments} 
			<div>
				<blockquote>{$comment.comment|nl2br} <br/>
				由 {$comment.user.username} 在 {$comment.commitTime|date_format:'%Y-%m-%d %H:%M'} 提交<br/><span></span>     
				</blockquote>
				<b>
				{if $comment.canEdit}[<my:a href="{$url_base}index.php?go=betterlife.comment.comment&comment_id={$comment.comment_id}&blog_id={$comment.blog_id}&pageNo={$smarty.get.pageNo|default:"1"}">改</my:a>]{/if}
				{if $comment.canDelete}[<my:a href="{$url_base}index.php?go=betterlife.comment.delete&comment_id={$comment.comment_id}&blog_id={$comment.blog_id}&pageNo={$smarty.get.pageNo|default:"1"}">删</my:a>]{/if}
				</b>
			</div>        
			{/foreach}
		{/if} 
		{if !$blog.canEdit}
		<div>
			<font color="{$color|default:'white'}">{$message|default:""}</font><br/>
			{if !$smarty.get.comment_id}<h2>提交新评论</h2> {else}<h2>修改评论</h2>{/if}                     
			<form name="commentForm" method="post">
				我要发言: <br/><input type="hidden" name="blog_id" value="{$blog.blog_id}"/>
				<textarea name="comment" id="comment" style="width:700px;height:300px;visibility:hidden;">{$content}</textarea><br/>
				<input type="submit" value="提交" class="btnSubmit" />
			</form>   
		</div> 
		{/if} 
	</div>
{/block}
