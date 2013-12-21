{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>查看用户</h1></div>
    <table class="viewdoblock">
        <tr class="entry"><td class="head">标识</th><td class="content">{$user.user_id}</td></tr> 
        <tr class="entry"><td class="head">部门标识</th><td class="content">{$user.department_id}</td></tr> 
        <tr class="entry"><td class="head">用户名</th><td class="content">{$user.username}</td></tr> 
        <tr class="entry"><td class="head">用户密码</th><td class="content">{$user.password}</td></tr> 
        <tr class="entry"><td class="head">邮箱地址</th><td class="content">{$user.email}</td></tr> 
    </table>
    <div align="center"><my:a href='index.php?go=model.user.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='index.php?go=model.user.edit&id={$user.id}&pageNo={$smarty.get.pageNo|default:"1"}'>修改用户</my:a></div>
</div>
{/block}