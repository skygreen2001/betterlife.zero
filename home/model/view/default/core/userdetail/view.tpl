{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
	<div><h1>查看用户详细信息</h1></div>
	<table class="viewdoblock">
        <tr class="entry"><th class="head">标识</th><td class="content">{$userdetail.ID}</td></tr> 
        <tr class="entry"><th class="head">用户标识</th><td class="content">{$userdetail.User_ID}</td></tr> 
        <tr class="entry"><th class="head">真实姓名</th><td class="content">{$userdetail.Realname}</td></tr> 
        <tr class="entry"><th class="head">头像</th><td class="content">
            <div class="wrap_2_inner"><img src="{$uploadImg_url|cat:$userdetail.Profile}" alt="头像"></div>
            <br/>存储相对路径:{$userdetail.Profile}</td></tr>
        <tr class="entry"><th class="head">国家</th><td class="content">{$userdetail.Country}</td></tr> 
        <tr class="entry"><th class="head">省</th><td class="content">{$userdetail.Province}</td></tr> 
        <tr class="entry"><th class="head">市</th><td class="content">{$userdetail.City}</td></tr> 
        <tr class="entry"><th class="head">区</th><td class="content">{$userdetail.District}</td></tr> 
        <tr class="entry"><th class="head">家庭住址</th><td class="content">{$userdetail.Address}</td></tr> 
        <tr class="entry"><th class="head">QQ号</th><td class="content">{$userdetail.Qq}</td></tr> 
        <tr class="entry"><th class="head">会员性别</th><td class="content">{$userdetail.Sex}</td></tr> 
        <tr class="entry"><th class="head">生日</th><td class="content">{$userdetail.Birthday}</td></tr> 
	</table>
	<div align="center"><my:a href='{$url_base}index.php?go=model.userdetail.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.userdetail.edit&id={$userdetail.id}&pageNo={$smarty.get.pageNo|default:"1"}'>修改用户详细信息</my:a></div>
</div>
{/block}