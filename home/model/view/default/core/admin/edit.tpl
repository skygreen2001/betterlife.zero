{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 <div class="block">
	<div><h1>编辑系统管理人员</h1><p><font color="red">{$message|default:''}</font></p></div>
	<form name="adminForm" method="post"><input type="hidden" name="ID" value="{$admin.ID}"/>
	<table class="viewdoblock">
        <tr class="entry"><th class="head">部门标识</th><td class="content"><input type="text" class="edit" name="Department_ID" value="{$admin.Department_ID}"/></td></tr>
        <tr class="entry"><th class="head">用户名</th><td class="content"><input type="text" class="edit" name="Username" value="{$admin.Username}"/></td></tr>
        <tr class="entry"><th class="head">真实姓名</th><td class="content"><input type="text" class="edit" name="Realname" value="{$admin.Realname}"/></td></tr>
        <tr class="entry"><th class="head">密码</th><td class="content"><input type="text" class="edit" name="Password" value="{$admin.Password}"/></td></tr>
        <tr class="entry"><th class="head">扮演角色</th><td class="content"><input type="text" class="edit" name="Roletype" value="{$admin.Roletype}"/></td></tr>
        <tr class="entry"><th class="head">视野</th><td class="content"><input type="text" class="edit" name="Seescope" value="{$admin.Seescope}"/></td></tr>
        <tr class="entry"><th class="head">登录次数</th><td class="content"><input type="text" class="edit" name="LoginTimes" value="{$admin.LoginTimes}"/></td></tr>
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>
	<div align="center"><my:a href='{$url_base}index.php?go=model.admin.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.admin.view&id={$admin.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看系统管理人员</my:a></div>
</div>
{/block}