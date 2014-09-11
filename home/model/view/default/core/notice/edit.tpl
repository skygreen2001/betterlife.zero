{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 {if ($online_editor=='KindEditor')}<script>
     showHtmlEditor("Notice_Content");</script>{/if}
 {if ($online_editor=='CKEditor')}
 {$editorHtml}
 <script>$(function(){
    ckeditor_replace_Notice_Content();});</script>
 {/if}
 {if ($online_editor=='xhEditor')}<script>$(function(){
    pageInit_Notice_Content();});</script>
 {/if}
 <div class="block">
	<div><h1>编辑通知</h1><p><font color="red">{$message|default:''}</font></p></div>
	<form name="noticeForm" method="post"><input type="hidden" name="ID" value="{$notice.ID}"/>
	<table class="viewdoblock">
        <tr class="entry"><th class="head">通知分类</th><td class="content"><input type="text" class="edit" name="NoticeType" value="{$notice.NoticeType}"/></td></tr>
        <tr class="entry"><th class="head">标题</th><td class="content"><input type="text" class="edit" name="Title" value="{$notice.Title}"/></td></tr>
        <tr class="entry"><th class="head">通知内容</th><td class="content">
        <textarea id="Notice_Content" name="Notice_Content" style="width:720px;height:300px;">{$notice.Notice_Content}</textarea>
        </td></tr>
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>
	<div align="center"><my:a href='{$url_base}index.php?go=model.notice.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.notice.view&id={$notice.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看通知</my:a></div>
</div>    {if ($online_editor=='UEditor')}
    <script>pageInit_ue_Notice_Content();</script>
    {/if}
{/block}