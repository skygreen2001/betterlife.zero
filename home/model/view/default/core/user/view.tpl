{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
	<div><h1>查看用户</h1></div>
	<table class="viewdoblock">
        <tr class="entry"><th class="head">用户标识</th><td class="content">{$user.ID}</td></tr> 
        <tr class="entry"><th class="head">用户名</th><td class="content">{$user.Username}</td></tr> 
        <tr class="entry"><th class="head">用户密码</th><td class="content">{$user.Password}</td></tr> 
        <tr class="entry"><th class="head">邮箱地址</th><td class="content">{$user.Email}</td></tr> 
        <tr class="entry"><th class="head">手机电话</th><td class="content">{$user.Cellphone}</td></tr> 
        <tr class="entry"><th class="head">访问次数</th><td class="content">{$user.LoginTimes}</td></tr> 
	</table>
	<div align="center"><my:a href='{$url_base}index.php?go=model.user.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.user.edit&id={$user.id}&pageNo={$smarty.get.pageNo|default:"1"}'>修改用户</my:a></div>
</div>
{/block}