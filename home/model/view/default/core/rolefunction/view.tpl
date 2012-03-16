{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">  
	<div><h1>查看角色拥有功能</h1></div>     
	<table class="viewdoblock">   
        <tr class="entry"><td class="head">角色编号</th><td class="content">{$rolefunction.roleId}</td></tr> 
        <tr class="entry"><td class="head">功能编号</th><td class="content">{$rolefunction.functionId}</td></tr>          
	</table>                                                            
	<div align="center"><my:a href='index.php?go=model.rolefunction.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='index.php?go=model.rolefunction.edit&id={$rolefunction.id}&pageNo={$smarty.get.pageNo|default:"1"}'>修改角色拥有功能</my:a></div>    
</div>
{/block}