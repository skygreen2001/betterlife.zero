{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 {if ($online_editor=='KindEditor')}<script>
     showHtmlEditor("Content");</script>{/if}
 {if ($online_editor=='CKEditor')}
 {$editorHtml}
 <script>$(function(){
    ckeditor_replace_Content();});</script>
 {/if}
 {if ($online_editor=='xhEditor')}<script>$(function(){
    pageInit_Content();});</script>
 {/if}
 <div class="block">
	<div><h1>编辑消息</h1><p><font color="red">{$message|default:''}</font></p></div>
	<form name="msgForm" method="post"><input type="hidden" name="ID" value="{$msg.ID}"/>
	<table class="viewdoblock">
        <tr class="entry"><th class="head">发送者</th><td class="content"><input type="text" class="edit" name="SenderId" value="{$msg.SenderId}"/></td></tr>
        <tr class="entry"><th class="head">接收者</th><td class="content"><input type="text" class="edit" name="ReceiverId" value="{$msg.ReceiverId}"/></td></tr>
        <tr class="entry"><th class="head">发送者名称</th><td class="content"><input type="text" class="edit" name="SenderName" value="{$msg.SenderName}"/></td></tr>
        <tr class="entry"><th class="head">接收者名称</th><td class="content"><input type="text" class="edit" name="ReceiverName" value="{$msg.ReceiverName}"/></td></tr>
        <tr class="entry"><th class="head">消息状态</th><td class="content"><input type="text" class="edit" name="Status" value="{$msg.Status}"/></td></tr>
        <tr class="entry"><th class="head">发送内容</th><td class="content">
        <textarea id="Content" name="Content" style="width:720px;height:300px;">{$msg.Content}</textarea>
        </td></tr>
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>
	<div align="center"><my:a href='{$url_base}index.php?go=model.msg.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.msg.view&id={$msg.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看消息</my:a></div>
</div>    {if ($online_editor=='UEditor')}
    <script>pageInit_ue_Content();</script>
    {/if}
{/block}