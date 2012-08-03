{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}   
 {if ($online_editor=='KindEditor')}<script>showHtmlEditor("content");//KindEditor 加载语句</script>{/if}
 {if ($online_editor=='KindEditor')}<script>showHtmlEditor("blog_name");//KindEditor 加载语句</script>{/if}
 {if ($online_editor=='CKEditor')}
 {$editorHtml}
 <script>$(function(){
	ckeditor_replace_blog_name();ckeditor_replace_content();});
 </script>
 {/if}
 {if ($online_editor=='xhEditor')}
 <script>$(function(){
	pageInit_blog_name();pageInit_content();});
 </script>
 {/if}
 
 <div class="block">  
	<div><h1>编辑博客</h1></div>
	<form name="blogForm" method="post"><input type="hidden" name="blog_id" value="{$blog.blog_id}"/>           
	<table class="viewdoblock">          
		<tr class="entry"><td class="head">用户标识</th><td class="content"><input type="text" class="edit" name="user_id" value="{$blog.user_id}"/></td></tr>
		<tr class="entry"><td class="head">博客名称</th><td class="content">
		<textarea id="blog_name" name="blog_name" style="width:93%;height:300px;visibility:hidden;">{$blog.blog_name}</textarea><br/>
		</td></tr>
		<tr class="entry"><td class="head">博客内容</th><td class="content">
		<textarea id="content" name="content" style="width:93%;height:300px;visibility:hidden;">{$blog.content}</textarea><br/>
		</td></tr>      
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>                                                            
	<div align="center"><my:a href='{$url_base}index.php?go=model.blog.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.blog.view&id={$blog.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看博客</my:a></div>    
</div>
{/block}