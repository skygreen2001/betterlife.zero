{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
	<div><h1>消息列表(共计{$countMsgs}个)</h1></div>
	<table class="viewdoblock">
		<tr class="entry">
            <th class="header">标识</th>
            <th class="header">发送者</th>
            <th class="header">接收者</th>
            <th class="header">发送者名称</th>
            <th class="header">接收者名称</th>
            <th class="header">发送内容</th>
            <th class="header">消息状态</th>
			<th class="header">操作</th>
		</tr>
		{foreach item=msg from=$msgs}
		<tr class="entry">
            <td class="content">{$msg.ID}</td>
            <td class="content">{$msg.SenderId}</td>
            <td class="content">{$msg.ReceiverId}</td>
            <td class="content">{$msg.SenderName}</td>
            <td class="content">{$msg.ReceiverName}</td>
            <td class="content">{$msg.Content}</td>
            <td class="content">{$msg.Status}</td>
			<td class="btnCol"><my:a href="{$url_base}index.php?go=model.msg.view&id={$msg.id}&pageNo={$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.msg.edit&id={$msg.id}&pageNo={$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{$url_base}index.php?go=model.msg.delete&id={$msg.id}&pageNo={$smarty.get.pageNo|default:"1"}">删除</my:a></td>
		</tr>
		{/foreach}
	</table>
	&nbsp;&nbsp;<my:page src='{$url_base}index.php?go=model.msg.lists' /><br/>
	<div align="center"><my:a href='{$url_base}index.php?go=model.msg.edit&pageNo={$smarty.get.pageNo|default:"1"}'>新建</my:a>|<my:a href='{$url_base}index.php?go=model.index.index'>返回首页</my:a></div>
</div>
{/block}