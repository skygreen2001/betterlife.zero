{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
	<div><h1>查看用户所属部门</h1></div>
	<table class="viewdoblock">
        <tr class="entry"><th class="head">编号</th><td class="content">{$department.ID}</td></tr> 
        <tr class="entry"><th class="head">部门名称</th><td class="content">{$department.Department_Name}</td></tr> 
        <tr class="entry"><th class="head">管理者</th><td class="content">{$department.Manager}</td></tr> 
        <tr class="entry"><th class="head">预算</th><td class="content">{$department.Budget}</td></tr> 
        <tr class="entry"><th class="head">实际开销</th><td class="content">{$department.Actualexpenses}</td></tr> 
        <tr class="entry"><th class="head">预估平均工资</th><td class="content">{$department.Estsalary}</td></tr> 
        <tr class="entry"><th class="head">实际工资</th><td class="content">{$department.Actualsalary}</td></tr> 
	</table>
	<div align="center"><my:a href='{$url_base}index.php?go=model.department.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.department.edit&id={$department.id}&pageNo={$smarty.get.pageNo|default:"1"}'>修改用户所属部门</my:a></div>
</div>
{/block}