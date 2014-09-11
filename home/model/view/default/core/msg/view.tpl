{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
	<div><h1>查看消息</h1></div>
	<table class="viewdoblock">
        <tr class="entry"><th class="head">标识</th><td class="content">{$msg.ID}</td></tr> 
        <tr class="entry"><th class="head">发送者</th><td class="content">{$msg.SenderId}</td></tr> 
        <tr class="entry"><th class="head">接收者</th><td class="content">{$msg.ReceiverId}</td></tr> 
        <tr class="entry"><th class="head">发送者名称</th><td class="content">{$msg.SenderName}</td></tr> 
        <tr class="entry"><th class="head">接收者名称</th><td class="content">{$msg.ReceiverName}</td></tr> 
        <tr class="entry"><th class="head">发送内容</th><td class="content">{$msg.Content}</td></tr> 
        <tr class="entry"><th class="head">消息状态</th><td class="content">{$msg.Status}</td></tr> 
	</table>
	<div align="center"><my:a href='{$url_base}index.php?go=model.msg.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.msg.edit&id={$msg.id}&pageNo={$smarty.get.pageNo|default:"1"}'>修改消息</my:a></div>
</div>
{/block}