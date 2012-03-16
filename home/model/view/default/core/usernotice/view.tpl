{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">  
	<div><h1>查看用户收到通知</h1></div>     
	<table class="viewdoblock">   
        <tr class="entry"><td class="head">用户编号</th><td class="content">{$usernotice.userId}</td></tr> 
        <tr class="entry"><td class="head">通知编号</th><td class="content">{$usernotice.noticeId}</td></tr>          
	</table>                                                            
	<div align="center"><my:a href='index.php?go=model.usernotice.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='index.php?go=model.usernotice.edit&id={$usernotice.id}&pageNo={$smarty.get.pageNo|default:"1"}'>修改用户收到通知</my:a></div>    
</div>
{/block}