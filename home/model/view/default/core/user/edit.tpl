{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 <div class="block">  
	<div><h1>编辑用户</h1></div>
	<form name="userForm" method="post"><input type="hidden" name="id" value="{$user.id}"/>           
	<table class="viewdoblock">                                                                                                                 
        <tr class="entry"><td class="head">部门编号</th><td class="content"><input type="text" class="edit" name="departmentId" value="{$user.departmentId}"/></td></tr>
        <tr class="entry"><td class="head">用户名</th><td class="content"><input type="text" class="edit" name="name" value="{$user.name}"/></td></tr>
        <tr class="entry"><td class="head">用户密码</th><td class="content"><input type="text" class="edit" name="password" value="{$user.password}"/></td></tr>       
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>                                                            
	<div align="center"><my:a href='{$url_base}index.php?go=model.user.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.user.view&id={$user.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看用户</my:a></div>    
</div>
{/block}