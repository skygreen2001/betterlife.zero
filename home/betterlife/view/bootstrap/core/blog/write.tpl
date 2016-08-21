{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
    {if ($online_editor=='KindEditor')}
    <script>
    KindEditor.ready(function(KE) {
        KE.create('textarea[name="blog_content"]',{$keConfig});
    });</script>
    {/if}
    {if ($online_editor=='CKEditor')}
    {$editorHtml}
    <script>$(function(){
         ckeditor_replace_blog_content();});</script>
     {/if}
     {if ($online_editor=='xhEditor')}
    <script>$(function(){
        pageInit_blog_content();});</script>
    {/if}

    <div class="contentBox" >
        <b><my:a href="{$url_base}index.php?go={$appName}.auth.logout">退出</my:a></b><br/>
        <my:a href="{$url_base}index.php?go={$appName}.blog.display&pageNo={$smarty.get.pageNo|default:"1"}">博客列表</my:a>
        <br/><font color="{$color}">{$message|nl2br|default:''}</font><br/>
        <form name="postForm" method="POST">
            博文名:<br/>
            <input type="text" class="inputNormal" style="width: 710px; margin-left: 0px;text-align: left;" name="blog_name" value="{$blog.blog_name}"/><br/>
            内容: <br/>
            <textarea id="blog_content" name="blog_content" style="width:710px;height:300px;">{$blog.blog_content}</textarea><br/>
            <input type="submit" value="提交" class="btnSubmit" />
        </form>
    </div>
    {if ($online_editor=='UEditor')}
    <script>pageInit_ue_blog_content();</script>
    {/if}
{/block}