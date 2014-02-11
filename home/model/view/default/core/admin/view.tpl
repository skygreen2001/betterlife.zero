{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
	<div><h1>查看系统管理人员</h1></div>
	<table class="viewdoblock">
        <tr class="entry"><th class="head">管理员标识</th><td class="content">{$admin.admin_id}</td></tr> 
        <tr class="entry"><th class="head">部门标识</th><td class="content">{$admin.department_id}</td></tr> 
        <tr class="entry"><th class="head">用户名</th><td class="content">{$admin.username}</td></tr> 
        <tr class="entry"><th class="head">真实姓名</th><td class="content">{$admin.realname}</td></tr> 
        <tr class="entry"><th class="head">密码</th><td class="content">{$admin.password}</td></tr> 
        <tr class="entry"><th class="head">扮演角色</th><td class="content">{$admin.roletype}</td></tr> 
        <tr class="entry"><th class="head">视野</th><td class="content">{$admin.seescope}</td></tr> 
        <tr class="entry"><th class="head">登录次数</th><td class="content">{$admin.loginTimes}</td></tr> 
	</table>
	<div align="center"><my:a href='{$url_base}index.php?go=model.admin.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.admin.edit&id={$admin.id}&pageNo={$smarty.get.pageNo|default:"1"}'>修改系统管理人员</my:a></div>
</div>
{/block}