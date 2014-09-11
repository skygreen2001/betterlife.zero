{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
	<div><h1>查看用户日志</h1></div>
	<table class="viewdoblock">
        <tr class="entry"><th class="head">标识</th><td class="content">{$loguser.ID}</td></tr> 
        <tr class="entry"><th class="head">用户标识</th><td class="content">{$loguser.User_ID}</td></tr> 
        <tr class="entry"><th class="head">类型</th><td class="content">{$loguser.UserType}</td></tr> 
        <tr class="entry"><th class="head">日志详情</th><td class="content">{$loguser.Log_Content}</td></tr> 
	</table>
	<div align="center"><my:a href='{$url_base}index.php?go=model.loguser.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.loguser.edit&id={$loguser.id}&pageNo={$smarty.get.pageNo|default:"1"}'>修改用户日志</my:a></div>
</div>
{/block}