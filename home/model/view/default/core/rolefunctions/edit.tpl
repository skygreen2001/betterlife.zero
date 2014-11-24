{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 <div class="block">
	<div><h1>{if $rolefunctions}编辑{else}新增{/if}角色拥有功能</h1><p><font color="red">{$message|default:''}</font></p></div>
	<form name="rolefunctionsForm" method="post"><input type="hidden" name="rolefunctions_id" value="{$rolefunctions.rolefunctions_id}"/>
	<table class="viewdoblock">
		<tr class="entry"><th class="head">角色标识</th><td class="content"><input type="text" class="edit" name="role_id" value="{$rolefunctions.role_id}"/></td></tr>
		<tr class="entry"><th class="head">功能标识</th><td class="content"><input type="text" class="edit" name="functions_id" value="{$rolefunctions.functions_id}"/></td></tr>
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>
	<div align="center"><my:a href='{$url_base}index.php?go=model.rolefunctions.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>{if $rolefunctions}|<my:a href='{$url_base}index.php?go=model.rolefunctions.view&id={$rolefunctions.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看角色拥有功能</my:a>{/if}</div>
</div>
{/block}