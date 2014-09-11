{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 <div class="block">
	<div><h1>编辑用户所属部门</h1><p><font color="red">{$message|default:''}</font></p></div>
	<form name="departmentForm" method="post"><input type="hidden" name="ID" value="{$department.ID}"/>
	<table class="viewdoblock">
        <tr class="entry"><th class="head">部门名称</th><td class="content"><input type="text" class="edit" name="Department_Name" value="{$department.Department_Name}"/></td></tr>
        <tr class="entry"><th class="head">管理者</th><td class="content"><input type="text" class="edit" name="Manager" value="{$department.Manager}"/></td></tr>
        <tr class="entry"><th class="head">预算</th><td class="content"><input type="text" class="edit" name="Budget" value="{$department.Budget}"/></td></tr>
        <tr class="entry"><th class="head">实际开销</th><td class="content"><input type="text" class="edit" name="Actualexpenses" value="{$department.Actualexpenses}"/></td></tr>
        <tr class="entry"><th class="head">预估平均工资</th><td class="content"><input type="text" class="edit" name="Estsalary" value="{$department.Estsalary}"/></td></tr>
        <tr class="entry"><th class="head">实际工资</th><td class="content"><input type="text" class="edit" name="Actualsalary" value="{$department.Actualsalary}"/></td></tr>
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>
	<div align="center"><my:a href='{$url_base}index.php?go=model.department.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.department.view&id={$department.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看用户所属部门</my:a></div>
</div>
{/block}