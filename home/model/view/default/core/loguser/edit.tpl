{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 {if ($online_editor=='KindEditor')}<script>
 	showHtmlEditor("log_content");</script>{/if}
 {if ($online_editor=='CKEditor')}
 {$editorHtml}
 <script>$(function(){
	ckeditor_replace_log_content();});</script>
 {/if}
 {if ($online_editor=='xhEditor')}<script>$(function(){
	pageInit_log_content();});</script>
 {/if}
 <div class="block">
	<div><h1>{if $loguser}编辑{else}新增{/if}用户日志</h1><p><font color="red">{$message|default:''}</font></p></div>
	<form name="loguserForm" method="post"><input type="hidden" name="loguser_id" value="{$loguser.loguser_id}"/>
	<table class="viewdoblock">
		<tr class="entry"><th class="head">标识</th><td class="content">{$loguser.loguser_id}</td></tr>
		<tr class="entry"><th class="head">用户标识</th><td class="content"><input type="text" class="edit" name="user_id" value="{$loguser.user_id}"/></td></tr>
		<tr class="entry"><th class="head">类型</th><td class="content"><input type="text" class="edit" name="userType" value="{$loguser.userType}"/></td></tr>
		<tr class="entry"><th class="head">日志详情</th><td class="content">
		<textarea id="log_content" name="log_content" style="width:720px;height:300px;">{$loguser.log_content}</textarea>
		</td></tr>
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>
	<div align="center"><my:a href='{$url_base}index.php?go=model.loguser.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>{if $loguser}|<my:a href='{$url_base}index.php?go=model.loguser.view&id={$loguser.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看用户日志</my:a>{/if}</div>
</div>	{if ($online_editor=='UEditor')}
	<script>pageInit_ue_log_content();</script>
	{/if}
{/block}