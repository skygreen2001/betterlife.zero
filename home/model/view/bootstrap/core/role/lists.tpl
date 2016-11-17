{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>角色列表(共计{$countRoles}个)</h1></div>
    <table class="viewdoblock">
        <tr class="entry">
            <th class="header">角色标识</th>
            <th class="header">角色名称</th>
            <th class="header">操作</th>
        </tr>
        {foreach item=role from=$roles}
        <tr class="entry">
            <td class="content">{$role.role_id}</td>
            <td class="content">{$role.role_name}</td>
            <td class="btnCol"><my:a href="{$url_base}index.php?go=model.role.view&amp;id={$role.role_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.role.edit&amp;id={$role.role_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{$url_base}index.php?go=model.role.delete&amp;id={$role.role_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}">删除</my:a></td>
        </tr>
        {/foreach}
    </table>

    <div class="footer" align="center">
        <div><my:page src='{$url_base}index.php?go=model.role.lists' /></div>
        <my:a href='{$url_base}index.php?go=model.role.edit&amp;pageNo={$smarty.get.pageNo|default:"1"}'>新建</my:a>|<my:a href='{$url_base}index.php?go=model.index.index'>返回首页</my:a>
    </div>
</div>
{/block}