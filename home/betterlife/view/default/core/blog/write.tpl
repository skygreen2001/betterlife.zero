{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
	{if ($online_editor=='KindEditor')}<script>showHtmlEditor("content");//KindEditor 加载语句</script>{/if}
	{if ($online_editor=='CKEditor')}
	{$editorHtml}
	<script>$(function(){
		 ckeditor_replace_content();});</script>
	 {/if}
	 {if ($online_editor=='xhEditor')}
	<script>$(function(){
		pageInit_content();});</script>
	{/if}
 
	<div class="contentBox">
		<b><my:a href="{$url_base}index.php?go=betterlife.auth.logout">退出</my:a></b><br/>
		<my:a href="{$url_base}index.php?go=betterlife.blog.display&pageNo={$smarty.get.pageNo|default:"1"}">博客列表</my:a>
		<br/><font color="{$color}">{$message|nl2br|default:''}</font><br/>
		<form name="postForm" method="POST">
			博文名:<br/>
			<input type="text" class="inputNormal" style="width: 620px;" name="blog_name" value="{$blog.blog_name}"/><br/>
			内容: <br/>  
			<textarea id="content" name="content" style="width:700px;height:300px;visibility:hidden;">{$blog.content}</textarea><br/> 
			<input type="submit" value="提交" class="btnSubmit" />
		</form>
	</div>
{/block}