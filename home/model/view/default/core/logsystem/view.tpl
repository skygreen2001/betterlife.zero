{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">  
	<div><h1>查看系统日志</h1></div>     
	<table class="viewdoblock">   
		<tr class="entry"><td class="head">日志记录时间</th><td class="content">{$logsystem.logtime}</td></tr> 
		<tr class="entry"><td class="head">分类</th><td class="content">{$logsystem.ident}</td></tr> 
		<tr class="entry"><td class="head">优先级</th><td class="content">{$logsystem.priority}</td></tr> 
		<tr class="entry"><td class="head">日志内容</th><td class="content">{$logsystem.message}</td></tr>          
	</table>                                                            
	<div align="center"><my:a href='index.php?go=model.logsystem.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a></div>    
</div>
{/block}