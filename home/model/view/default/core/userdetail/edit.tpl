{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 <div class="block">
	<div><h1>编辑用户详细信息</h1><p><font color="red">{$message|default:''}</font></p></div>
	<form name="userdetailForm" method="post" enctype="multipart/form-data"><input type="hidden" name="userdetail_id" value="{$userdetail.userdetail_id}"/>
	<table class="viewdoblock">
        <tr class="entry"><th class="head">用户标识</th><td class="content"><input type="text" class="edit" name="user_id" value="{$userdetail.user_id}"/></td></tr>
        <tr class="entry"><th class="head">真实姓名</th><td class="content"><input type="text" class="edit" name="realname" value="{$userdetail.realname}"/></td></tr>
        <tr class="entry"><th class="head">头像</th><td class="content"><input type="file" class="edit" name="profileUpload" accept="image/png,image/gif,image/jpg,image/jpeg" value="{$userdetail.profile}"/></td></tr>
        <tr class="entry"><th class="head">国家</th><td class="content"><input type="text" class="edit" name="country" value="{$userdetail.country}"/></td></tr>
        <tr class="entry"><th class="head">省</th><td class="content"><input type="text" class="edit" name="province" value="{$userdetail.province}"/></td></tr>
        <tr class="entry"><th class="head">市</th><td class="content"><input type="text" class="edit" name="city" value="{$userdetail.city}"/></td></tr>
        <tr class="entry"><th class="head">区</th><td class="content"><input type="text" class="edit" name="district" value="{$userdetail.district}"/></td></tr>
        <tr class="entry"><th class="head">家庭住址</th><td class="content"><input type="text" class="edit" name="address" value="{$userdetail.address}"/></td></tr>
        <tr class="entry"><th class="head">QQ号</th><td class="content"><input type="text" class="edit" name="qq" value="{$userdetail.qq}"/></td></tr>
        <tr class="entry"><th class="head">会员性别</th><td class="content"><input type="text" class="edit" name="sex" value="{$userdetail.sex}"/></td></tr>
        <tr class="entry"><th class="head">生日</th><td class="content"><input type="text" class="edit" name="birthday" value="{$userdetail.birthday}"/></td></tr>
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>
	<div align="center"><my:a href='{$url_base}index.php?go=model.userdetail.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.userdetail.view&id={$userdetail.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看用户详细信息</my:a></div>
</div>
{/block}