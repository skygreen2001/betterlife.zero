{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>用户日志列表(共计{$countLogusers}个)</h1></div>
    <table class="viewdoblock">
        <tr class="entry">
            <th class="header">标识</th>
            <th class="header">用户</th>
            <th class="header">类型</th>
            <th class="header">日志详情</th>
            <th class="header">操作</th>
        </tr>
        {foreach item=loguser from=$logusers}
        <tr class="entry">
            <td class="content">{$loguser.loguser_id}</td>
            <td class="content">{$loguser.username}</td>
            <td class="content">{$loguser.userTypeShow}</td>
            <td class="content">{$loguser.log_content}</td>
            <td class="btnCol"><my:a href="{$url_base}index.php?go=model.loguser.view&amp;id={$loguser.loguser_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.loguser.edit&amp;id={$loguser.loguser_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{$url_base}index.php?go=model.loguser.delete&amp;id={$loguser.loguser_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}">删除</my:a></td>
        </tr>
        {/foreach}
    </table>

    <div class="footer" align="center">
        <div><my:page src='{$url_base}index.php?go=model.loguser.lists' /></div>
        <my:a href='{$url_base}index.php?go=model.loguser.edit&amp;pageNo={$smarty.get.pageNo|default:"1"}'>新建</my:a>|<my:a href='{$url_base}index.php?go=model.index.index'>返回首页</my:a>
    </div>
</div>
{/block}