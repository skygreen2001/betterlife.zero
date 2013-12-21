{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>查看通知</h1></div>
    <table class="viewdoblock">
        <tr class="entry"><td class="head">编号</th><td class="content">{$notice.notice_id}</td></tr> 
        <tr class="entry"><td class="head">分类</th><td class="content">{$notice.noticeType}</td></tr> 
        <tr class="entry"><td class="head">标题</th><td class="content">{$notice.title}</td></tr> 
        <tr class="entry"><td class="head">通知内容</th><td class="content">{$notice.notice_content}</td></tr> 
    </table>
    <div align="center"><my:a href='index.php?go=model.notice.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='index.php?go=model.notice.edit&id={$notice.id}&pageNo={$smarty.get.pageNo|default:"1"}'>修改通知</my:a></div>
</div>
{/block}