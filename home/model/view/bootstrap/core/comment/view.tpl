{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>查看评论</h1></div>
    <table class="viewdoblock">
        <tr class="entry"><th class="head">标识</th><td class="content">{$comment.comment_id}</td></tr>
        <tr class="entry"><th class="head">评论者</th><td class="content">{$comment.username}</td></tr>
        <tr class="entry"><th class="head">评论者标识</th><td class="content">{$comment.user_id}</td></tr>
        <tr class="entry"><th class="head">评论</th><td class="content">{$comment.comment}</td></tr>
        <tr class="entry"><th class="head">博客</th><td class="content">{$comment.blog_name}</td></tr>
        <tr class="entry"><th class="head">博客标识</th><td class="content">{$comment.blog_id}</td></tr>
    </table>
    <div class="footer" align="center"><my:a href='{$url_base}index.php?go=model.comment.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.comment.edit&amp;id={$comment.comment_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>修改评论</my:a></div>
</div>
{/block}