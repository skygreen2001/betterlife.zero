{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 <div class="block">  
	<div><h1>编辑用户详细信息</h1></div>
	<form name="userdetailForm" method="post"><input type="hidden" name="userdetail_id" value="{$userdetail.userdetail_id}"/>           
	<table class="viewdoblock">                                                                                                                 
        <tr class="entry"><td class="head">用户标识</th><td class="content"><input type="text" class="edit" name="user_id" value="{$userdetail.user_id}"/></td></tr>
        <tr class="entry"><td class="head">邮件地址</th><td class="content"><input type="text" class="edit" name="email" value="{$userdetail.email}"/></td></tr>
        <tr class="entry"><td class="head">手机号码</th><td class="content"><input type="text" class="edit" name="cellphone" value="{$userdetail.cellphone}"/></td></tr>       
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>                                                            
	<div align="center"><my:a href='{$url_base}index.php?go=model.userdetail.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.userdetail.view&id={$userdetail.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看用户详细信息</my:a></div>    
</div>
{/block}