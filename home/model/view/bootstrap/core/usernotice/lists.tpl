{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>用户收到通知列表(共计{$countUsernotices}个)</h1></div>
    <table class="viewdoblock">
        <tr class="entry">
            <th class="header">标识</th>
            <th class="header">用户</th>
            <th class="header">通知</th>
            <th class="header">操作</th>
        </tr>
        {foreach item=usernotice from=$usernotices}
        <tr class="entry">
            <td class="content">{$usernotice.usernotice_id}</td>
            <td class="content">{$usernotice.username}</td>
            <td class="content">{$usernotice.noticeType}</td>
            <td class="btnCol"><my:a href="{$url_base}index.php?go=model.usernotice.view&id={$usernotice.usernotice_id}&pageNo={$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.usernotice.edit&id={$usernotice.usernotice_id}&pageNo={$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{$url_base}index.php?go=model.usernotice.delete&id={$usernotice.usernotice_id}&pageNo={$smarty.get.pageNo|default:"1"}">删除</my:a></td>
        </tr>
        {/foreach}
    </table>
    &nbsp;&nbsp;<my:page src='{$url_base}index.php?go=model.usernotice.lists' /><br/>
    <div align="center"><my:a href='{$url_base}index.php?go=model.usernotice.edit&pageNo={$smarty.get.pageNo|default:"1"}'>新建</my:a>|<my:a href='{$url_base}index.php?go=model.index.index'>返回首页</my:a></div>
</div>
{/block}