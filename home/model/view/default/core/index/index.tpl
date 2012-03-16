{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
    <div><h1>这是首页列表(共计数据对象15个)</h1></div>
    <table class="viewdoblock" style="width: 500px;">
        <tr class="entry"><td class="content"><a href="{$url_base}index.php?go=model.blog.lists">博客</a></td></tr>
        <tr class="entry"><td class="content"><a href="{$url_base}index.php?go=model.comment.lists">评论</a></td></tr>
        <tr class="entry"><td class="content"><a href="{$url_base}index.php?go=model.region.lists">地区</a></td></tr>
        <tr class="entry"><td class="content"><a href="{$url_base}index.php?go=model.logsystem.lists">系统日志</a></td></tr>
        <tr class="entry"><td class="content"><a href="{$url_base}index.php?go=model.loguser.lists">用户日志</a></td></tr>
        <tr class="entry"><td class="content"><a href="{$url_base}index.php?go=model.msg.lists">消息</a></td></tr>
        <tr class="entry"><td class="content"><a href="{$url_base}index.php?go=model.notice.lists">通知</a></td></tr>
        <tr class="entry"><td class="content"><a href="{$url_base}index.php?go=model.usernotice.lists">用户收到通知</a></td></tr>
        <tr class="entry"><td class="content"><a href="{$url_base}index.php?go=model.department.lists">用户所属部门</a></td></tr>
        <tr class="entry"><td class="content"><a href="{$url_base}index.php?go=model.Functions.lists">功能信息</a></td></tr>
        <tr class="entry"><td class="content"><a href="{$url_base}index.php?go=model.rolefunction.lists">角色拥有功能</a></td></tr>
        <tr class="entry"><td class="content"><a href="{$url_base}index.php?go=model.userrole.lists">用户角色</a></td></tr>
        <tr class="entry"><td class="content"><a href="{$url_base}index.php?go=model.role.lists">角色</a></td></tr>
        <tr class="entry"><td class="content"><a href="{$url_base}index.php?go=model.user.lists">用户</a></td></tr>
        <tr class="entry"><td class="content"><a href="{$url_base}index.php?go=model.userdetail.lists">用户详细信息</a></td></tr>
    </table>
        

{/block}