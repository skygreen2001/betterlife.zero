{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">  
	<div><h1>查看角色</h1></div>     
	<table class="viewdoblock">   
        <tr class="entry"><td class="head">角色名称</th><td class="content">{$role.name}</td></tr>          
	</table>                                                            
	<div align="center"><my:a href='index.php?go=model.role.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='index.php?go=model.role.edit&id={$role.id}&pageNo={$smarty.get.pageNo|default:"1"}'>修改角色</my:a></div>    
</div>
{/block}