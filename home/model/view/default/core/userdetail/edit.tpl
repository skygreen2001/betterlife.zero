{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 <div class="block">
	<div><h1>编辑用户详细信息</h1><p><font color="red">{$message|default:''}</font></p></div>
	<form name="userdetailForm" method="post" enctype="multipart/form-data"><input type="hidden" name="ID" value="{$userdetail.ID}"/>
	<table class="viewdoblock">
        <tr class="entry"><th class="head">用户标识</th><td class="content"><input type="text" class="edit" name="User_ID" value="{$userdetail.User_ID}"/></td></tr>
        <tr class="entry"><th class="head">真实姓名</th><td class="content"><input type="text" class="edit" name="Realname" value="{$userdetail.Realname}"/></td></tr>
        <tr class="entry"><th class="head">头像</th><td class="content"><input type="file" class="edit" name="ProfileUpload" accept="image/png,image/gif,image/jpg,image/jpeg" value="{$userdetail.Profile}"/></td></tr>
        <tr class="entry"><th class="head">国家</th><td class="content"><input type="text" class="edit" name="Country" value="{$userdetail.Country}"/></td></tr>
        <tr class="entry"><th class="head">省</th><td class="content"><input type="text" class="edit" name="Province" value="{$userdetail.Province}"/></td></tr>
        <tr class="entry"><th class="head">市</th><td class="content"><input type="text" class="edit" name="City" value="{$userdetail.City}"/></td></tr>
        <tr class="entry"><th class="head">区</th><td class="content"><input type="text" class="edit" name="District" value="{$userdetail.District}"/></td></tr>
        <tr class="entry"><th class="head">家庭住址</th><td class="content"><input type="text" class="edit" name="Address" value="{$userdetail.Address}"/></td></tr>
        <tr class="entry"><th class="head">QQ号</th><td class="content"><input type="text" class="edit" name="Qq" value="{$userdetail.Qq}"/></td></tr>
        <tr class="entry"><th class="head">会员性别</th><td class="content"><input type="text" class="edit" name="Sex" value="{$userdetail.Sex}"/></td></tr>
        <tr class="entry"><th class="head">生日</th><td class="content"><input type="text" class="edit" name="Birthday" value="{$userdetail.Birthday}"/></td></tr>
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>
	<div align="center"><my:a href='{$url_base}index.php?go=model.userdetail.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.userdetail.view&id={$userdetail.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看用户详细信息</my:a></div>
</div>
{/block}