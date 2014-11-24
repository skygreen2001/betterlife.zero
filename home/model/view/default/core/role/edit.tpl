{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 <div class="block">
	<div><h1>{if $role}编辑{else}新增{/if}角色</h1><p><font color="red">{$message|default:''}</font></p></div>
	<form name="roleForm" method="post"><input type="hidden" name="role_id" value="{$role.role_id}"/>
	<table class="viewdoblock">
		<tr class="entry"><th class="head">角色名称</th><td class="content"><input type="text" class="edit" name="role_name" value="{$role.role_name}"/></td></tr>
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>
	<div align="center"><my:a href='{$url_base}index.php?go=model.role.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>{if $role}|<my:a href='{$url_base}index.php?go=model.role.view&id={$role.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看角色</my:a>{/if}</div>
</div>
{/block}