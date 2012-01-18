{* Flexy 语法写法   
<script>showHtmlEditor("postForm","content");</script>
<b><my:a href="{url_base}index.php?g=betterlife&m=auth&a=logout">退出</my:a></b><br/>
<my:a href="{url_base}index.php?g=betterlife&m=blog&a=display&pageNo={_GET[pageNo]}">博客列表</my:a>
<br/><font color="{color}">{message}</font><br/>
<form method="POST">
    博文名:<br/>
    <input type="text" name="name" value="{post.name}"/><br/>
    内容: <br/>
    <textarea rows="5" cols="60" name="content">{post.content}</textarea><br/>
    <input type="submit" />
</form>
 <!--Smarty 模板的写法-->
*}
{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
    {*<script>showHtmlEditor("postForm","content");//KindEditor 加载语句</script>*}
    <div class="contentBox">
        <b><my:a href="{$url_base}index.php?go=betterlife.auth.logout">退出</my:a></b><br/>
        <my:a href="{$url_base}index.php?go=betterlife.blog.display&pageNo={$smarty.get.pageNo|default:"1"}">博客列表</my:a>
        <br/><font color="{$color}">{$message|nl2br|default:''}</font><br/>
        <form name="postForm" method="POST">
            博文名:<br/>
            <input type="text" name="name" value="{$post.name}"/><br/>
            内容: <br/>  
            {* <textarea id="content" name="content" style="width:700px;height:300px;visibility:hidden;">{$post.content}</textarea><br/> *}
            {$editorHtml}<br/>     
            <input type="submit" />
        </form>
    </div>
{/block}