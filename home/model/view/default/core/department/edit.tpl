{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
     <div class="block">
        <div><h1>{if $department}编辑{else}新增{/if}用户所属部门</h1><p><font color="red">{$message|default:''}</font></p></div>
        <form name="departmentForm" method="post"><input type="hidden" name="department_id" value="{$department.department_id}"/>
        <table class="viewdoblock">
        {if $department}
        <tr class="entry"><th class="head">编号</th><td class="content">{$department.department_id}</td></tr>
        {/if}
        <tr class="entry"><th class="head">部门名称</th><td class="content"><input type="text" class="edit" name="department_name" value="{$department.department_name}"/></td></tr>
        <tr class="entry"><th class="head">管理者</th><td class="content"><input type="text" class="edit" name="manager" value="{$department.manager}"/></td></tr>
        <tr class="entry"><th class="head">预算</th><td class="content"><input type="text" class="edit" name="budget" value="{$department.budget}"/></td></tr>
        <tr class="entry"><th class="head">实际开销</th><td class="content"><input type="text" class="edit" name="actualexpenses" value="{$department.actualexpenses}"/></td></tr>
        <tr class="entry"><th class="head">预估平均工资</th><td class="content"><input type="text" class="edit" name="estsalary" value="{$department.estsalary}"/></td></tr>
        <tr class="entry"><th class="head">实际工资</th><td class="content"><input type="text" class="edit" name="actualsalary" value="{$department.actualsalary}"/></td></tr>
            <tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
        </table>
        </form>
        <div class="footer" align="center">
            <my:a href='{$url_base}index.php?go=model.department.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>
            {if $department}
            |<my:a href='{$url_base}index.php?go=model.department.view&amp;id={$department.id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>查看用户所属部门</my:a>
            {/if}
        </div>
    </div>

{/block}