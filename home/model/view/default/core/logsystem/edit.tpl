{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 <div class="block">
	<div><h1>{if $logsystem}编辑{else}新增{/if}系统日志</h1><p><font color="red">{$message|default:''}</font></p></div>
	<form name="logsystemForm" method="post"><input type="hidden" name="logsystem_id" value="{$logsystem.logsystem_id}"/>
	<table class="viewdoblock">
		<tr class="entry"><th class="head">日志记录时间</th><td class="content"><input type="text" class="edit" name="logtime" value="{$logsystem.logtime}"/></td></tr>
		<tr class="entry"><th class="head">分类</th><td class="content"><input type="text" class="edit" name="ident" value="{$logsystem.ident}"/></td></tr>
		<tr class="entry"><th class="head">优先级</th><td class="content"><input type="text" class="edit" name="priority" value="{$logsystem.priority}"/></td></tr>
		<tr class="entry"><th class="head">日志内容</th><td class="content"><input type="text" class="edit" name="message" value="{$logsystem.message}"/></td></tr>
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>
	<div align="center"><my:a href='{$url_base}index.php?go=model.logsystem.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>{if $logsystem}|<my:a href='{$url_base}index.php?go=model.logsystem.view&id={$logsystem.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看系统日志</my:a>{/if}</div>
</div>
{/block}