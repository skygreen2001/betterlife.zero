{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>评论列表(共计{$countComments}个)</h1></div>
    <table class="viewdoblock">
        <tr class="entry">
            <th class="header">标识</th>
            <th class="header">评论者</th>
            <th class="header">评论</th>
            <th class="header">博客</th>
            <th class="header">操作</th>
        </tr>
        {foreach item=comment from=$comments}
        <tr class="entry">
            <td class="content">{$comment.comment_id}</td>
            <td class="content">{$comment.username}</td>
            <td class="content">{$comment.comment}</td>
            <td class="content">{$comment.blog_name}</td>
            <td class="btnCol"><my:a href="{$url_base}index.php?go=model.comment.view&amp;id={$comment.comment_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.comment.edit&amp;id={$comment.comment_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{$url_base}index.php?go=model.comment.delete&amp;id={$comment.comment_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}">删除</my:a></td>
        </tr>
        {/foreach}
    </table>

    <div class="footer" align="center">
        <div><my:page src='{$url_base}index.php?go=model.comment.lists' /></div>
        <my:a href='{$url_base}index.php?go=model.comment.edit&amp;pageNo={$smarty.get.pageNo|default:"1"}'>新建</my:a>|<my:a href='{$url_base}index.php?go=model.index.index'>返回首页</my:a>
    </div>
</div>
{/block}