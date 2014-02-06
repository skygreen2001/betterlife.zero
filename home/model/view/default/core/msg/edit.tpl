{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 {if ($online_editor=='KindEditor')}<script>
     showHtmlEditor("content");</script>{/if}
 {if ($online_editor=='CKEditor')}
 {$editorHtml}
 <script>$(function(){
    ckeditor_replace_content();});</script>
 {/if}
 {if ($online_editor=='xhEditor')}<script>$(function(){
    pageInit_content();});</script>
 {/if}
 <div class="block">
    <div><h1>编辑消息</h1></div>
    <form name="msgForm" method="post"><input type="hidden" name="msg_id" value="{$msg.msg_id}"/>
    <table class="viewdoblock">
        <tr class="entry"><th class="head">发送者</th><td class="content"><input type="text" class="edit" name="senderId" value="{$msg.senderId}"/></td></tr>
        <tr class="entry"><th class="head">接收者</th><td class="content"><input type="text" class="edit" name="receiverId" value="{$msg.receiverId}"/></td></tr>
        <tr class="entry"><th class="head">发送者名称</th><td class="content"><input type="text" class="edit" name="senderName" value="{$msg.senderName}"/></td></tr>
        <tr class="entry"><th class="head">接收者名称</th><td class="content"><input type="text" class="edit" name="receiverName" value="{$msg.receiverName}"/></td></tr>
        <tr class="entry"><th class="head">消息状态</th><td class="content"><input type="text" class="edit" name="status" value="{$msg.status}"/></td></tr>
        <tr class="entry"><th class="head">发送内容</th><td class="content">
        <textarea id="content" name="content" style="width:93%;height:300px;visibility:hidden;">{$msg.content}</textarea>
        </td></tr>
        <tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
    </table>
    </form>
    <div align="center"><my:a href='{$url_base}index.php?go=model.msg.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.msg.view&id={$msg.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看消息</my:a></div>
</div>
{/block}