{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 <div class="block">
	<div><h1>编辑用户收到通知</h1><p><font color="red">{$message|default:''}</font></p></div>
	<form name="usernoticeForm" method="post"><input type="hidden" name="usernotice_id" value="{$usernotice.usernotice_id}"/>
	<table class="viewdoblock">
        <tr class="entry"><th class="head">用户编号</th><td class="content"><input type="text" class="edit" name="user_id" value="{$usernotice.user_id}"/></td></tr>
        <tr class="entry"><th class="head">通知编号</th><td class="content"><input type="text" class="edit" name="notice_id" value="{$usernotice.notice_id}"/></td></tr>
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>
	<div align="center"><my:a href='{$url_base}index.php?go=model.usernotice.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.usernotice.view&id={$usernotice.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看用户收到通知</my:a></div>
</div>
{/block}