{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
	<div><h1>系统管理人员列表(共计{$countAdmins}个)</h1></div>
	<table class="viewdoblock">
		<tr class="entry">
			<th class="header">管理员标识</th>
			<th class="header">部门标识</th>
			<th class="header">用户名</th>
			<th class="header">真实姓名</th>
			<th class="header">密码</th>
			<th class="header">扮演角色</th>
			<th class="header">视野</th>
			<th class="header">登录次数</th>
			<th class="header">操作</th>
		</tr>
		{foreach item=admin from=$admins}
		<tr class="entry">
			<td class="content">{$admin.admin_id}</td>
			<td class="content">{$admin.department_id}</td>
			<td class="content">{$admin.username}</td>
			<td class="content">{$admin.realname}</td>
			<td class="content">{$admin.password}</td>
			<td class="content">{$admin.roletype}</td>
			<td class="content">{$admin.seescope}</td>
			<td class="content">{$admin.loginTimes}</td>
			<td class="btnCol"><my:a href="{$url_base}index.php?go=model.admin.view&id={$admin.id}&pageNo={$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.admin.edit&id={$admin.id}&pageNo={$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{$url_base}index.php?go=model.admin.delete&id={$admin.id}&pageNo={$smarty.get.pageNo|default:"1"}">删除</my:a></td>
		</tr>
		{/foreach}
	</table>
	&nbsp;&nbsp;<my:page src='{$url_base}index.php?go=model.admin.lists' /><br/>
	<div align="center"><my:a href='{$url_base}index.php?go=model.admin.edit&pageNo={$smarty.get.pageNo|default:"1"}'>新建</my:a>|<my:a href='{$url_base}index.php?go=model.index.index'>返回首页</my:a></div>
</div>
{/block}