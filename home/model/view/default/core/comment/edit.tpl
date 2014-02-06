{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 {if ($online_editor=='KindEditor')}<script>
     showHtmlEditor("comment");</script>{/if}
 {if ($online_editor=='CKEditor')}
 {$editorHtml}
 <script>$(function(){
    ckeditor_replace_comment();});</script>
 {/if}
 {if ($online_editor=='xhEditor')}<script>$(function(){
    pageInit_comment();});</script>
 {/if}
 <div class="block">
    <div><h1>编辑评论</h1></div>
    <form name="commentForm" method="post"><input type="hidden" name="comment_id" value="{$comment.comment_id}"/>
    <table class="viewdoblock">
        <tr class="entry"><th class="head">评论者标识</th><td class="content"><input type="text" class="edit" name="user_id" value="{$comment.user_id}"/></td></tr>
        <tr class="entry"><th class="head">博客标识</th><td class="content"><input type="text" class="edit" name="blog_id" value="{$comment.blog_id}"/></td></tr>
        <tr class="entry"><th class="head">评论</th><td class="content">
        <textarea id="comment" name="comment" style="width:93%;height:300px;visibility:hidden;">{$comment.comment}</textarea>
        </td></tr>
        <tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
    </table>
    </form>
    <div align="center"><my:a href='{$url_base}index.php?go=model.comment.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.comment.view&id={$comment.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看评论</my:a></div>
</div>
{/block}