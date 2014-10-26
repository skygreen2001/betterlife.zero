{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
	<div><h1>用户所属部门列表(共计{$countDepartments}个)</h1></div>
	<table class="viewdoblock">
		<tr class="entry">
			<th class="header">编号</th>
			<th class="header">部门名称</th>
			<th class="header">管理者</th>
			<th class="header">预算</th>
			<th class="header">实际开销</th>
			<th class="header">预估平均工资</th>
			<th class="header">实际工资</th>
			<th class="header">操作</th>
		</tr>
		{foreach item=department from=$departments}
		<tr class="entry">
			<td class="content">{$department.department_id}</td>
			<td class="content">{$department.department_name}</td>
			<td class="content">{$department.manager}</td>
			<td class="content">{$department.budget}</td>
			<td class="content">{$department.actualexpenses}</td>
			<td class="content">{$department.estsalary}</td>
			<td class="content">{$department.actualsalary}</td>
			<td class="btnCol"><my:a href="{$url_base}index.php?go=model.department.view&id={$department.id}&pageNo={$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.department.edit&id={$department.id}&pageNo={$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{$url_base}index.php?go=model.department.delete&id={$department.id}&pageNo={$smarty.get.pageNo|default:"1"}">删除</my:a></td>
		</tr>
		{/foreach}
	</table>
	&nbsp;&nbsp;<my:page src='{$url_base}index.php?go=model.department.lists' /><br/>
	<div align="center"><my:a href='{$url_base}index.php?go=model.department.edit&pageNo={$smarty.get.pageNo|default:"1"}'>新建</my:a>|<my:a href='{$url_base}index.php?go=model.index.index'>返回首页</my:a></div>
</div>
{/block}