{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 <div class="block">  
	<div><h1>编辑通知</h1></div>
	<form name="noticeForm" method="post"><input type="hidden" name="notice_id" value="{$notice.notice_id}"/>           
	<table class="viewdoblock">                                                                                                                 
        <tr class="entry"><td class="head">管理员编号</th><td class="content"><input type="text" class="edit" name="user_id" value="{$notice.user_id}"/></td></tr>
        <tr class="entry"><td class="head">分类</th><td class="content"><input type="text" class="edit" name="group" value="{$notice.group}"/></td></tr>
        <tr class="entry"><td class="head">标题</th><td class="content"><input type="text" class="edit" name="title" value="{$notice.title}"/></td></tr>
        <tr class="entry"><td class="head">通知内容</th><td class="content"><input type="text" class="edit" name="content" value="{$notice.content}"/></td></tr>       
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>                                                            
	<div align="center"><my:a href='{$url_base}index.php?go=model.notice.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.notice.view&id={$notice.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看通知</my:a></div>    
</div>
{/block}