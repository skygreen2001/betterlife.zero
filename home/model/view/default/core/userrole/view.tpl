{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>查看用户角色</h1></div>
    <table class="viewdoblock">
        <tr class="entry"><th class="head">标识</th><td class="content">{$userrole.userrole_id}</td></tr>
        <tr class="entry"><th class="head">用户</th><td class="content">{$userrole.username}</td></tr>
        <tr class="entry"><th class="head">用户标识</th><td class="content">{$userrole.user_id}</td></tr>
        <tr class="entry"><th class="head">角色</th><td class="content">{$userrole.role_name}</td></tr>
        <tr class="entry"><th class="head">角色标识</th><td class="content">{$userrole.role_id}</td></tr>
    </table>
    <div class="footer" align="center"><my:a href='{$url_base}index.php?go=model.userrole.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.userrole.edit&amp;id={$userrole.userrole_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>修改用户角色</my:a></div>
</div>
{/block}