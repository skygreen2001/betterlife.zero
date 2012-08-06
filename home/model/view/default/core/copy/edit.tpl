{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 <div class="block">  
	<div><h1>编辑系统管理人员</h1></div>
	<form name="copyForm" method="post"><input type="hidden" name="" value="{$copy.}"/>           
	<table class="viewdoblock"> 
        <tr class="entry"><td class="head"></th><td class="content"><input type="text" class="edit" name="admin_id" value="{$copy.admin_id}"/></td></tr>
        <tr class="entry"><td class="head">用户名</th><td class="content"><input type="text" class="edit" name="username" value="{$copy.username}"/></td></tr>
        <tr class="entry"><td class="head">真实姓名</th><td class="content"><input type="text" class="edit" name="realname" value="{$copy.realname}"/></td></tr>
        <tr class="entry"><td class="head">密码</th><td class="content"><input type="text" class="edit" name="password" value="{$copy.password}"/></td></tr>
        <tr class="entry"><td class="head">扮演角色</th><td class="content"><input type="text" class="edit" name="roletype" value="{$copy.roletype}"/></td></tr>
        <tr class="entry"><td class="head">角色标识</th><td class="content"><input type="text" class="edit" name="roleid" value="{$copy.roleid}"/></td></tr>
        <tr class="entry"><td class="head">视野</th><td class="content"><input type="text" class="edit" name="seescope" value="{$copy.seescope}"/></td></tr>       
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>                                                            
	<div align="center"><my:a href='{$url_base}index.php?go=model.copy.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.copy.view&id={$copy.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看系统管理人员</my:a></div>    
</div>
{/block}