{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">  
	<div><h1>系统日志列表(共计{$countLogsystems}个)</h1></div>     
	<table class="viewdoblock">
		<tr class="entry">
			<th class="header">日志记录时间</th>
			<th class="header">分类</th>
			<th class="header">优先级</th>
			<th class="header">日志内容</th>                                  
			<th class="header">操作</th>
		</tr>       
		{foreach item=logsystem from=$logsystems}     
		<tr class="entry">                            
			<td class="content">{$logsystem.logtime}</td>
			<td class="content">{$logsystem.ident}</td>
			<td class="content">{$logsystem.priority}</td>
			<td class="content">{$logsystem.message}</td>
			<td class="btnCol"><my:a href="{$url_base}index.php?go=model.logsystem.view&id={$logsystem.id}&pageNo={$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.logsystem.delete&id={$logsystem.id}&pageNo={$smarty.get.pageNo|default:"1"}">删除</my:a></td>
		</tr> 
		{/foreach}                                                           
	</table> 
	&nbsp;&nbsp;<my:page src='{$url_base}index.php?go=model.logsystem.lists' /><br/>   
	<div align="center"><my:a href='{$url_base}index.php?go=model.index.index'>返回首页</my:a></div>    
</div>
{/block}