{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 <div class="block">
	<div><h1>编辑用户</h1><p><font color="red">{$message|default:''}</font></p></div>
	<form name="userForm" method="post"><input type="hidden" name="ID" value="{$user.ID}"/>
	<table class="viewdoblock">
        <tr class="entry"><th class="head">用户名</th><td class="content"><input type="text" class="edit" name="Username" value="{$user.Username}"/></td></tr>
        <tr class="entry"><th class="head">用户密码</th><td class="content"><input type="text" class="edit" name="Password" value="{$user.Password}"/></td></tr>
        <tr class="entry"><th class="head">邮箱地址</th><td class="content"><input type="text" class="edit" name="Email" value="{$user.Email}"/></td></tr>
        <tr class="entry"><th class="head">手机电话</th><td class="content"><input type="text" class="edit" name="Cellphone" value="{$user.Cellphone}"/></td></tr>
        <tr class="entry"><th class="head">访问次数</th><td class="content"><input type="text" class="edit" name="LoginTimes" value="{$user.LoginTimes}"/></td></tr>
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>
	<div align="center"><my:a href='{$url_base}index.php?go=model.user.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.user.view&id={$user.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看用户</my:a></div>
</div>
{/block}