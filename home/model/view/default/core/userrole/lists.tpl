{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>用户角色列表(共计{$countUserroles}个)</h1></div>
    <table class="viewdoblock">
        <tr class="entry">
            <th class="header">标识</th>
            <th class="header">用户标识</th>
            <th class="header">角色标识</th>
            <th class="header">操作</th>
        </tr>
        {foreach item=userrole from=$userroles}
        <tr class="entry">
            <td class="content">{$userrole.userrole_id}</td>
            <td class="content">{$userrole.user_id}</td>
            <td class="content">{$userrole.role_id}</td>
            <td class="btnCol"><my:a href="{$url_base}index.php?go=model.userrole.view&id={$userrole.id}&pageNo={$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.userrole.edit&id={$userrole.id}&pageNo={$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{$url_base}index.php?go=model.userrole.delete&id={$userrole.id}&pageNo={$smarty.get.pageNo|default:"1"}">删除</my:a></td>
        </tr>
        {/foreach}
    </table>
    &nbsp;&nbsp;<my:page src='{$url_base}index.php?go=model.userrole.lists' /><br/>
    <div align="center"><my:a href='{$url_base}index.php?go=model.userrole.edit&pageNo={$smarty.get.pageNo|default:"1"}'>新建</my:a>|<my:a href='{$url_base}index.php?go=model.index.index'>返回首页</my:a></div>
</div>
{/block}