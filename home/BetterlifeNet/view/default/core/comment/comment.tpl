{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
	{if isset($online_editor)}
		{if ($online_editor=='KindEditor')}
		<script>
		KindEditor.ready(function(KE) {
			KE.create('textarea[name="Comment"]',{$keConfig});
		});</script>
		{/if}
		{if ($online_editor=='CKEditor')}
		{$editorHtml}
		<script>$(function(){
			 ckeditor_replace_comment();});</script>
		 {/if}
		 {if ($online_editor=='xhEditor')}
		<script>$(function(){
			pageInit_comment();});</script>
		{/if}
	{/if}
	<div id="content" class="contentBox">
		<my:a href='{$url_base}?go=BetterlifeNet.auth.logout'><b>退出</b></my:a><br/>
		<my:a href='{$url_base}?go=BetterlifeNet.blog.display&pageNo={$smarty.get.pageNo|default:"1"}'><b>博客列表</b></my:a>
		<div id='blog{$blog.blog_id}' >
			<h1>{$blog.Blog_Name}</h1>
			<p>{$blog.Blog_Content|nl2br}</p>
			评论数:{$blog.count_comments}
		</div>
		{if !isset($smarty.get.comment_id)}
			{foreach item=comment from=$blog.comments}
			<div>
				<blockquote>{$comment.comment|nl2br} <br/>
				由 {$comment.user.Username} 在 {$comment.CommitTime|date_format:'%Y-%m-%d %H:%M'} 提交<br/><span></span>
				</blockquote>
				<b>
				{if $comment.canEdit}[<my:a href="{$url_base}index.php?go=BetterlifeNet.comment.comment&comment_id={$comment.ID}&blog_id={$comment.Blog_ID}&pageNo={$smarty.get.pageNo|default:"1"}">改</my:a>]{/if}
				{if $comment.canDelete}[<my:a href="{$url_base}index.php?go=BetterlifeNet.comment.delete&comment_id={$comment.ID}&blog_id={$comment.Blog_ID}&pageNo={$smarty.get.pageNo|default:"1"}">删</my:a>]{/if}
				</b>
			</div>
			{/foreach}
		{/if}
		{if !$blog.canEdit}
		<div>
			<font color="{$color|default:'white'}">{$message|default:""}</font><br/>
			{if !isset($smarty.get.comment_id)}<h2>提交新评论</h2> {else}<h2>修改评论</h2>{/if}
			<form name="commentForm" method="post">
				我要发言: <br/><input type="hidden" name="Blog_ID" value="{$blog.ID}"/><input type="hidden" name="ID" value="{$comment_id}"/>
				<textarea name="Comment" id="comment" style="width:720px;height:300px;">{if isset($comment_content)}{$comment_content}{/if}</textarea><br/>
				<input type="submit" value="提交" class="btnSubmit" /> | <input class="btnSubmit" onclick="location.href='{$url_base}index.php?go=betterlife.comment.comment&blog_id={$smarty.get.blog_id}&pageNo={$smarty.get.pageNo|default:"1"}'" type="button" value="返回" />
			</form>     
            {if ($online_editor=='UEditor')}            
            <script>pageInit_ue_comment();</script>
            {/if}
		</div>
		{/if}
	</div>
{/block}