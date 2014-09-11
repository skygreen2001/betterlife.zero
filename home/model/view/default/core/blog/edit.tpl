{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 {if ($online_editor=='KindEditor')}<script>
     showHtmlEditor("Blog_Content");</script>{/if}
 {if ($online_editor=='CKEditor')}
 {$editorHtml}
 <script>$(function(){
    ckeditor_replace_Blog_Content();});</script>
 {/if}
 {if ($online_editor=='xhEditor')}<script>$(function(){
    pageInit_Blog_Content();});</script>
 {/if}
 <div class="block">
	<div><h1>编辑博客</h1><p><font color="red">{$message|default:''}</font></p></div>
	<form name="blogForm" method="post"><input type="hidden" name="ID" value="{$blog.ID}"/>
	<table class="viewdoblock">
        <tr class="entry"><th class="head">用户标识</th><td class="content"><input type="text" class="edit" name="User_ID" value="{$blog.User_ID}"/></td></tr>
        <tr class="entry"><th class="head">博客标题</th><td class="content"><input type="text" class="edit" name="Blog_Name" value="{$blog.Blog_Name}"/></td></tr>
        <tr class="entry"><th class="head">博客内容</th><td class="content">
        <textarea id="Blog_Content" name="Blog_Content" style="width:720px;height:300px;">{$blog.Blog_Content}</textarea>
        </td></tr>
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>
	<div align="center"><my:a href='{$url_base}index.php?go=model.blog.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.blog.view&id={$blog.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看博客</my:a></div>
</div>    {if ($online_editor=='UEditor')}
    <script>pageInit_ue_Blog_Content();</script>
    {/if}
{/block}