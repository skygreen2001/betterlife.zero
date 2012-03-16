{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 <div class="block">  
	<div><h1>编辑用户收到通知</h1></div>
	<form name="usernoticeForm" method="post"><input type="hidden" name="id" value="{$usernotice.id}"/>           
	<table class="viewdoblock">                                                                                                                 
        <tr class="entry"><td class="head">用户编号</th><td class="content"><input type="text" class="edit" name="userId" value="{$usernotice.userId}"/></td></tr>
        <tr class="entry"><td class="head">通知编号</th><td class="content"><input type="text" class="edit" name="noticeId" value="{$usernotice.noticeId}"/></td></tr>       
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>                                                            
	<div align="center"><my:a href='{$url_base}index.php?go=model.usernotice.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.usernotice.view&id={$usernotice.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看用户收到通知</my:a></div>    
</div>
{/block}