{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
	{if ($online_editor=='KindEditor')}<script>showHtmlEditor("postForm","content");//KindEditor 加载语句</script>{/if}
	<div class="contentBox">
		<b><my:a href="{$url_base}index.php?go=betterlife.auth.logout">退出</my:a></b><br/>
		<my:a href="{$url_base}index.php?go=betterlife.blog.display&pageNo={$smarty.get.pageNo|default:"1"}">博客列表</my:a>
		<br/><font color="{$color}">{$message|nl2br|default:''}</font><br/>
		<form name="postForm" method="POST">
			博文名:<br/>
			<input type="text" name="blog_name" value="{$blog.blog_name}"/><br/>
			内容: <br/>  
			{if ($online_editor=='xhEditor'||$online_editor=='KindEditor')}<textarea id="content" name="content" style="width:700px;height:300px;visibility:hidden;">{$blog.content}</textarea><br/>{/if}
			{if ($online_editor=='CKEditor')}{$editorHtml}<br/>{/if}     
			<input type="submit" value="提交" class="btnSubmit" />
		</form>
	</div>
{/block}