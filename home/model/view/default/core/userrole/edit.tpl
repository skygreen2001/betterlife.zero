{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 <div class="block">  
	<div><h1>编辑用户角色</h1></div>
	<form name="userroleForm" method="post"><input type="hidden" name="id" value="{$userrole.id}"/>           
	<table class="viewdoblock">                                                                                                                 
        <tr class="entry"><td class="head">用户编号</th><td class="content"><input type="text" class="edit" name="userId" value="{$userrole.userId}"/></td></tr>
        <tr class="entry"><td class="head">角色编号</th><td class="content"><input type="text" class="edit" name="roleId" value="{$userrole.roleId}"/></td></tr>       
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>                                                            
	<div align="center"><my:a href='{$url_base}index.php?go=model.userrole.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.userrole.view&id={$userrole.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看用户角色</my:a></div>    
</div>
{/block}