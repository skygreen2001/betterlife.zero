{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>查看用户详细信息</h1></div>
    <table class="viewdoblock">
        <tr class="entry"><td class="head">标识</th><td class="content">{$userdetail.userdetail_id}</td></tr> 
        <tr class="entry"><td class="head">用户标识</th><td class="content">{$userdetail.user_id}</td></tr> 
        <tr class="entry"><td class="head">真实姓名</th><td class="content">{$userdetail.realname}</td></tr> 
        <tr class="entry"><td class="head">地区标识</th><td class="content">{$userdetail.region_id}</td></tr> 
        <tr class="entry"><td class="head">头像</th><td class="content">{$userdetail.profile}</td></tr> 
        <tr class="entry"><td class="head">家庭住址</th><td class="content">{$userdetail.address}</td></tr> 
        <tr class="entry"><td class="head">QQ号</th><td class="content">{$userdetail.qq}</td></tr> 
        <tr class="entry"><td class="head">会员性别</th><td class="content">{$userdetail.sex}</td></tr> 
        <tr class="entry"><td class="head">生日</th><td class="content">{$userdetail.birthday}</td></tr> 
    </table>
    <div align="center"><my:a href='index.php?go=model.userdetail.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='index.php?go=model.userdetail.edit&id={$userdetail.id}&pageNo={$smarty.get.pageNo|default:"1"}'>修改用户详细信息</my:a></div>
</div>
{/block}