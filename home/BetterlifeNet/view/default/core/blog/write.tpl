{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
	{if ($online_editor=='KindEditor')}
	<script>
	KindEditor.ready(function(KE) {
		KE.create('textarea[name="Blog_Content"]',{$keConfig});
	});</script>
	{/if}
	{if ($online_editor=='CKEditor')}
	{$editorHtml}
	<script>$(function(){   
		 ckeditor_replace_blog_content();});</script>
	{/if}
	{if ($online_editor=='xhEditor')}
	<script>$(function(){
		pageInit_blog_content();});</script>
	{/if}   
	<div class="contentBox">
		<b><my:a href="{$url_base}index.php?go=BetterlifeNet.auth.logout">退出</my:a></b><br/>
		<my:a href="{$url_base}index.php?go=BetterlifeNet.blog.display&pageNo={$smarty.get.pageNo|default:"1"}">博客列表</my:a>
		<br/><font color="{$color}">{$message|nl2br|default:''}</font><br/>
		<form name="postForm" method="POST">
			博文名:<br/>
			<input type="text" name="Blog_Name" class="inputNormal" style="width: 720px; margin-left: 0px;text-align: left;" value="{$blog.Blog_Name}"/><br/>
			内容: <br/>  
			<textarea id="blog_content" name="Blog_Content" style="width:720px;height:300px;">{$blog.Blog_Content}</textarea><br/> 
			<input type="submit" value="提交" class="btnSubmit" />
		</form>
	</div>
    {if ($online_editor=='UEditor')}            
    <script>pageInit_ue_blog_content();</script>
    {/if}
{/block}