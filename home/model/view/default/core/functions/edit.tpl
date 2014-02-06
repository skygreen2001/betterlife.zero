{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 {if ($online_editor=='KindEditor')}<script>
     showHtmlEditor("url");</script>{/if}
 {if ($online_editor=='CKEditor')}
 {$editorHtml}
 <script>$(function(){
    ckeditor_replace_url();});</script>
 {/if}
 {if ($online_editor=='xhEditor')}<script>$(function(){
    pageInit_url();});</script>
 {/if}
 <div class="block">
    <div><h1>编辑功能信息</h1></div>
    <form name="functionsForm" method="post"><input type="hidden" name="functions_id" value="{$functions.functions_id}"/>
    <table class="viewdoblock">
        <tr class="entry"><th class="head">允许访问的URL权限</th><td class="content">
        <textarea id="url" name="url" style="width:93%;height:300px;visibility:hidden;">{$functions.url}</textarea>
        </td></tr>
        <tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
    </table>
    </form>
    <div align="center"><my:a href='{$url_base}index.php?go=model.functions.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.functions.view&id={$functions.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看功能信息</my:a></div>
</div>
{/block}