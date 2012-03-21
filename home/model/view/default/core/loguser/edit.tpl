{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 <div class="block">  
	<div><h1>编辑用户日志</h1></div>
	<form name="loguserForm" method="post"><input type="hidden" name="loguser_id" value="{$loguser.loguser_id}"/>           
	<table class="viewdoblock">                                                                                                                 
        <tr class="entry"><td class="head">用户标识</th><td class="content"><input type="text" class="edit" name="user_id" value="{$loguser.user_id}"/></td></tr>
        <tr class="entry"><td class="head">类型</th><td class="content"><input type="text" class="edit" name="userType" value="{$loguser.userType}"/></td></tr>
        <tr class="entry"><td class="head">日志详情</th><td class="content"><input type="text" class="edit" name="content" value="{$loguser.content}"/></td></tr>       
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>                                                            
	<div align="center"><my:a href='{$url_base}index.php?go=model.loguser.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.loguser.view&id={$loguser.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看用户日志</my:a></div>    
</div>
{/block}