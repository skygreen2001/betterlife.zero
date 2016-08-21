{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>查看用户</h1></div>
    <table class="viewdoblock">
        <tr class="entry"><th class="head">用户标识</th><td class="content">{$user.user_id}</td></tr>
        <tr class="entry"><th class="head">用户名</th><td class="content">{$user.username}</td></tr>
        <tr class="entry"><th class="head">用户密码</th><td class="content">{$user.password}</td></tr>
        <tr class="entry"><th class="head">邮箱地址</th><td class="content">{$user.email}</td></tr>
        <tr class="entry"><th class="head">手机电话</th><td class="content">{$user.cellphone}</td></tr>
        <tr class="entry"><th class="head">访问次数</th><td class="content">{$user.loginTimes}</td></tr>
    </table>
    <div align="center"><my:a href='{$url_base}index.php?go=model.user.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.user.edit&id={$user.user_id}&pageNo={$smarty.get.pageNo|default:"1"}'>修改用户</my:a></div>

    <div><h3>用户的博客(共计{$countBlogs}个)</h3></div>
    <table class="viewdoblock">
        <tr class="entry">
            <th class="header">标识</th>
            <th class="header">用户</th>
            <th class="header">博客标题</th>
            <th class="header">博客内容</th>
            <th class="header">操作</th>
        </tr>
        {foreach item=blog from=$blogs}
        <tr class="entry">
            <td class="content">{$blog.blog_id}</td>
            <td class="content">{$blog.username}</td>
            <td class="content">{$blog.blog_name}</td>
            <td class="content">{$blog.blog_content}</td>
            <td class="btnCol"><my:a href="{$url_base}index.php?go=model.blog.view&id={$blog.blog_id}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.blog.edit&id={$blog.blog_id}">修改</my:a>|<my:a href="{$url_base}index.php?go=model.blog.delete&id={$blog.blog_id}">删除</my:a></td>
        </tr>
        {/foreach}
    </table>

    <div><h3>用户的评论(共计{$countComments}个)</h3></div>
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
            <td class="btnCol"><my:a href="{$url_base}index.php?go=model.comment.view&id={$comment.comment_id}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.comment.edit&id={$comment.comment_id}">修改</my:a>|<my:a href="{$url_base}index.php?go=model.comment.delete&id={$comment.comment_id}">删除</my:a></td>
        </tr>
        {/foreach}
    </table>
</div>
{/block}