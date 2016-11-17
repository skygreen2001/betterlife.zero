{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>查看用户收到通知</h1></div>
    <table class="viewdoblock">
        <tr class="entry"><th class="head">标识</th><td class="content">{$usernotice.usernotice_id}</td></tr>
        <tr class="entry"><th class="head">用户</th><td class="content">{$usernotice.username}</td></tr>
        <tr class="entry"><th class="head">用户编号</th><td class="content">{$usernotice.user_id}</td></tr>
        <tr class="entry"><th class="head">通知</th><td class="content">{$usernotice.noticeType}</td></tr>
        <tr class="entry"><th class="head">通知编号</th><td class="content">{$usernotice.notice_id}</td></tr>
    </table>
    <div class="footer" align="center"><my:a href='{$url_base}index.php?go=model.usernotice.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.usernotice.edit&amp;id={$usernotice.usernotice_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>修改用户收到通知</my:a></div>
</div>
{/block}