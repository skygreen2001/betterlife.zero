{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>查看用户所属部门</h1></div>
    <table class="viewdoblock">
        <tr class="entry"><td class="head">编号</th><td class="content">{$department.department_id}</td></tr> 
        <tr class="entry"><td class="head">部门名称</th><td class="content">{$department.department_name}</td></tr> 
        <tr class="entry"><td class="head">管理者</th><td class="content">{$department.manager}</td></tr> 
        <tr class="entry"><td class="head">预算</th><td class="content">{$department.budget}</td></tr> 
        <tr class="entry"><td class="head">实际开销</th><td class="content">{$department.actualexpenses}</td></tr> 
        <tr class="entry"><td class="head">预估平均工资</th><td class="content">{$department.estsalary}</td></tr> 
        <tr class="entry"><td class="head">实际工资</th><td class="content">{$department.actualsalary}</td></tr> 
    </table>
    <div align="center"><my:a href='index.php?go=model.department.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='index.php?go=model.department.edit&id={$department.id}&pageNo={$smarty.get.pageNo|default:"1"}'>修改用户所属部门</my:a></div>
</div>
{/block}