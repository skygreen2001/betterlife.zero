{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 {if ($online_editor=='KindEditor')}<script>
     showHtmlEditor("Log_Content");</script>{/if}
 {if ($online_editor=='CKEditor')}
 {$editorHtml}
 <script>$(function(){
    ckeditor_replace_Log_Content();});</script>
 {/if}
 {if ($online_editor=='xhEditor')}<script>$(function(){
    pageInit_Log_Content();});</script>
 {/if}
 <div class="block">
	<div><h1>编辑用户日志</h1><p><font color="red">{$message|default:''}</font></p></div>
	<form name="loguserForm" method="post"><input type="hidden" name="ID" value="{$loguser.ID}"/>
	<table class="viewdoblock">
        <tr class="entry"><th class="head">用户标识</th><td class="content"><input type="text" class="edit" name="User_ID" value="{$loguser.User_ID}"/></td></tr>
        <tr class="entry"><th class="head">类型</th><td class="content"><input type="text" class="edit" name="UserType" value="{$loguser.UserType}"/></td></tr>
        <tr class="entry"><th class="head">日志详情</th><td class="content">
        <textarea id="Log_Content" name="Log_Content" style="width:720px;height:300px;">{$loguser.Log_Content}</textarea>
        </td></tr>
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>
	<div align="center"><my:a href='{$url_base}index.php?go=model.loguser.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.loguser.view&id={$loguser.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看用户日志</my:a></div>
</div>    {if ($online_editor=='UEditor')}
    <script>pageInit_ue_Log_Content();</script>
    {/if}
{/block}