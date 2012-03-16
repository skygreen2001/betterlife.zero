{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">  
	<div><h1>查看用户角色</h1></div>     
	<table class="viewdoblock">   
        <tr class="entry"><td class="head">用户编号</th><td class="content">{$userrole.userId}</td></tr> 
        <tr class="entry"><td class="head">角色编号</th><td class="content">{$userrole.roleId}</td></tr>          
	</table>                                                            
	<div align="center"><my:a href='index.php?go=model.userrole.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='index.php?go=model.userrole.edit&id={$userrole.id}&pageNo={$smarty.get.pageNo|default:"1"}'>修改用户角色</my:a></div>    
</div>
{/block}