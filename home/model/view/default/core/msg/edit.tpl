{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 <div class="block">  
	<div><h1>编辑消息</h1></div>
	<form name="msgForm" method="post"><input type="hidden" name="id" value="{$msg.id}"/>           
	<table class="viewdoblock">                                                                                                                 
        <tr class="entry"><td class="head">发送者</th><td class="content"><input type="text" class="edit" name="senderId" value="{$msg.senderId}"/></td></tr>
        <tr class="entry"><td class="head">接收者</th><td class="content"><input type="text" class="edit" name="receiverId" value="{$msg.receiverId}"/></td></tr>
        <tr class="entry"><td class="head">发送者名称</th><td class="content"><input type="text" class="edit" name="senderName" value="{$msg.senderName}"/></td></tr>
        <tr class="entry"><td class="head">接收者名称</th><td class="content"><input type="text" class="edit" name="receiverName" value="{$msg.receiverName}"/></td></tr>
        <tr class="entry"><td class="head">发送内容</th><td class="content"><input type="text" class="edit" name="content" value="{$msg.content}"/></td></tr>
        <tr class="entry"><td class="head">消息状态</th><td class="content"><input type="text" class="edit" name="status" value="{$msg.status}"/></td></tr>       
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>                                                            
	<div align="center"><my:a href='{$url_base}index.php?go=model.msg.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.msg.view&id={$msg.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看消息</my:a></div>    
</div>
{/block}