{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">  
	<div><h1>查看用户日志</h1></div>     
	<table class="viewdoblock">   
		<tr class="entry"><td class="head">用户编号</th><td class="content">{$loguser.userId}</td></tr> 
		<tr class="entry"><td class="head">类型</th><td class="content">{$loguser.userType}</td></tr> 
		<tr class="entry"><td class="head">日志详情</th><td class="content">{$loguser.content}</td></tr>          
	</table>                                                            
	<div align="center"><my:a href='index.php?go=model.loguser.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a></div>    
</div>
{/block}