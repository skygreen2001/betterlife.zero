{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 <div class="block">
	<div><h1>编辑角色拥有功能</h1><p><font color="red">{$message|default:''}</font></p></div>
	<form name="rolefunctionsForm" method="post"><input type="hidden" name="ID" value="{$rolefunctions.ID}"/>
	<table class="viewdoblock">
        <tr class="entry"><th class="head">角色标识</th><td class="content"><input type="text" class="edit" name="Role_ID" value="{$rolefunctions.Role_ID}"/></td></tr>
        <tr class="entry"><th class="head">功能标识</th><td class="content"><input type="text" class="edit" name="Functions_ID" value="{$rolefunctions.Functions_ID}"/></td></tr>
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>
	<div align="center"><my:a href='{$url_base}index.php?go=model.rolefunctions.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.rolefunctions.view&id={$rolefunctions.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看角色拥有功能</my:a></div>
</div>
{/block}