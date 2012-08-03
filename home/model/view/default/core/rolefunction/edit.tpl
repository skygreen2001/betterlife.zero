{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 <div class="block">  
	<div><h1>编辑角色拥有功能</h1></div>
	<form name="rolefunctionForm" method="post"><input type="hidden" name="rolefunction_id" value="{$rolefunction.rolefunction_id}"/>           
	<table class="viewdoblock"> 
        <tr class="entry"><td class="head">角色标识</th><td class="content"><input type="text" class="edit" name="role_id" value="{$rolefunction.role_id}"/></td></tr>
        <tr class="entry"><td class="head">功能标识</th><td class="content"><input type="text" class="edit" name="function_id" value="{$rolefunction.function_id}"/></td></tr>       
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>                                                            
	<div align="center"><my:a href='{$url_base}index.php?go=model.rolefunction.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.rolefunction.view&id={$rolefunction.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看角色拥有功能</my:a></div>    
</div>
{/block}