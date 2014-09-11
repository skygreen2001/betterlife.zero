{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 <div class="block">
	<div><h1>编辑用户角色</h1><p><font color="red">{$message|default:''}</font></p></div>
	<form name="userroleForm" method="post"><input type="hidden" name="ID" value="{$userrole.ID}"/>
	<table class="viewdoblock">
        <tr class="entry"><th class="head">用户标识</th><td class="content"><input type="text" class="edit" name="User_ID" value="{$userrole.User_ID}"/></td></tr>
        <tr class="entry"><th class="head">角色标识</th><td class="content"><input type="text" class="edit" name="Role_ID" value="{$userrole.Role_ID}"/></td></tr>
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>
	<div align="center"><my:a href='{$url_base}index.php?go=model.userrole.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.userrole.view&id={$userrole.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看用户角色</my:a></div>
</div>
{/block}