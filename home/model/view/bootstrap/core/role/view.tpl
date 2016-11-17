{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>查看角色</h1></div>
    <table class="viewdoblock">
        <tr class="entry"><th class="head">角色标识</th><td class="content">{$role.role_id}</td></tr>
        <tr class="entry"><th class="head">角色名称</th><td class="content">{$role.role_name}</td></tr>
    </table>
    <div class="footer" align="center"><my:a href='{$url_base}index.php?go=model.role.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.role.edit&amp;id={$role.role_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>修改角色</my:a></div>
</div>
{/block}