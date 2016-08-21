{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 {if ($online_editor=='KindEditor')}<script>
     showHtmlEditor("notice_content");</script>{/if}
 {if ($online_editor=='CKEditor')}
 {$editorHtml}
 <script>$(function(){
    ckeditor_replace_notice_content();});</script>
 {/if}
 {if ($online_editor=='xhEditor')}<script>$(function(){
    pageInit_notice_content();});</script>
 {/if}
 <div class="block">
    <div><h1>{if $notice}编辑{else}新增{/if}通知</h1><p><font color="red">{$message|default:''}</font></p></div>
    <form name="noticeForm" method="post"><input type="hidden" name="notice_id" value="{$notice.notice_id}"/>
    <table class="viewdoblock">
        {if $notice}<tr class="entry"><th class="head">编号</th><td class="content">{$notice.notice_id}</td></tr>{/if}
        <tr class="entry"><th class="head">通知分类</th><td class="content"><input type="text" class="edit" name="noticeType" value="{$notice.noticeType}"/></td></tr>
        <tr class="entry"><th class="head">标题</th><td class="content"><input type="text" class="edit" name="title" value="{$notice.title}"/></td></tr>
        <tr class="entry"><th class="head">通知内容</th><td class="content">
        <textarea id="notice_content" name="notice_content" style="width:720px;height:300px;">{$notice.notice_content}</textarea>
        </td></tr>
        <tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
    </table>
    </form>
    <div align="center"><my:a href='{$url_base}index.php?go=model.notice.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>{if $notice}|<my:a href='{$url_base}index.php?go=model.notice.view&id={$notice.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看通知</my:a>{/if}</div>
</div>    {if ($online_editor=='UEditor')}
    <script>pageInit_ue_notice_content();</script>
    {/if}
{/block}