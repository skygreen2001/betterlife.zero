{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 <div class="block">
    <div><h1>{if $admin}编辑{else}新增{/if}系统管理人员</h1><p><font color="red">{$message|default:''}</font></p></div>
    <form name="adminForm" method="post"><input type="hidden" name="admin_id" value="{$admin.admin_id}"/>
    <table class="viewdoblock">
        {if $admin}<tr class="entry"><th class="head">管理员标识</th><td class="content">{$admin.admin_id}</td></tr>{/if}
        <tr class="entry"><th class="head">部门标识</th><td class="content"><input type="text" class="edit" name="department_id" value="{$admin.department_id}"/></td></tr>
        <tr class="entry"><th class="head">用户名</th><td class="content"><input type="text" class="edit" name="username" value="{$admin.username}"/></td></tr>
        <tr class="entry"><th class="head">真实姓名</th><td class="content"><input type="text" class="edit" name="realname" value="{$admin.realname}"/></td></tr>
        <tr class="entry"><th class="head">密码</th><td class="content"><input type="text" class="edit" name="password" value="{$admin.password}"/></td></tr>
        <tr class="entry"><th class="head">扮演角色</th><td class="content"><input type="text" class="edit" name="roletype" value="{$admin.roletype}"/></td></tr>
        <tr class="entry"><th class="head">视野</th><td class="content"><input type="text" class="edit" name="seescope" value="{$admin.seescope}"/></td></tr>
        <tr class="entry"><th class="head">登录次数</th><td class="content"><input type="text" class="edit" name="loginTimes" value="{$admin.loginTimes}"/></td></tr>
        <tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
    </table>
    </form>
    <div align="center"><my:a href='{$url_base}index.php?go=model.admin.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>{if $admin}|<my:a href='{$url_base}index.php?go=model.admin.view&id={$admin.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看系统管理人员</my:a>{/if}</div>
</div>
{/block}