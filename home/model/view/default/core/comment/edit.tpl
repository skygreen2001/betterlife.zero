{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 {if ($online_editor=='KindEditor')}<script>
     showHtmlEditor("Comment");</script>{/if}
 {if ($online_editor=='CKEditor')}
 {$editorHtml}
 <script>$(function(){
    ckeditor_replace_Comment();});</script>
 {/if}
 {if ($online_editor=='xhEditor')}<script>$(function(){
    pageInit_Comment();});</script>
 {/if}
 <div class="block">
	<div><h1>编辑评论</h1><p><font color="red">{$message|default:''}</font></p></div>
	<form name="commentForm" method="post"><input type="hidden" name="ID" value="{$comment.ID}"/>
	<table class="viewdoblock">
        <tr class="entry"><th class="head">评论者标识</th><td class="content"><input type="text" class="edit" name="User_ID" value="{$comment.User_ID}"/></td></tr>
        <tr class="entry"><th class="head">博客标识</th><td class="content"><input type="text" class="edit" name="Blog_ID" value="{$comment.Blog_ID}"/></td></tr>
        <tr class="entry"><th class="head">评论</th><td class="content">
        <textarea id="Comment" name="Comment" style="width:720px;height:300px;">{$comment.Comment}</textarea>
        </td></tr>
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>
	<div align="center"><my:a href='{$url_base}index.php?go=model.comment.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.comment.view&id={$comment.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看评论</my:a></div>
</div>    {if ($online_editor=='UEditor')}
    <script>pageInit_ue_Comment();</script>
    {/if}
{/block}