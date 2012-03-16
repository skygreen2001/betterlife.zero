{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 <div class="block">  
	<div><h1>编辑用户所属部门</h1></div>
	<form name="departmentForm" method="post"><input type="hidden" name="id" value="{$department.id}"/>           
	<table class="viewdoblock">                                                                                                                 
        <tr class="entry"><td class="head">部门名称</th><td class="content"><input type="text" class="edit" name="name" value="{$department.name}"/></td></tr>
        <tr class="entry"><td class="head">管理者</th><td class="content"><input type="text" class="edit" name="manager" value="{$department.manager}"/></td></tr>
        <tr class="entry"><td class="head">预算</th><td class="content"><input type="text" class="edit" name="budget" value="{$department.budget}"/></td></tr>
        <tr class="entry"><td class="head">实际开销</th><td class="content"><input type="text" class="edit" name="actualexpenses" value="{$department.actualexpenses}"/></td></tr>
        <tr class="entry"><td class="head">部门人员预估平均工资</th><td class="content"><input type="text" class="edit" name="estsalary" value="{$department.estsalary}"/></td></tr>
        <tr class="entry"><td class="head">部门人员实际平均工资</th><td class="content"><input type="text" class="edit" name="actualsalary" value="{$department.actualsalary}"/></td></tr>       
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>                                                            
	<div align="center"><my:a href='{$url_base}index.php?go=model.department.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.department.view&id={$department.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看用户所属部门</my:a></div>    
</div>
{/block}