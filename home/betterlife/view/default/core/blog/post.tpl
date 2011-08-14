{* Flexy 语法写法   
<script>showHtmlEditor("commentForm","comment");</script>              
<b><a href='{url_base}?g=betterlife&m=auth&a=logout'>退出</a></b><br/>
<b><a href='{url_base}?g=betterlife&m=blog&a=display&pageNo={_GET[pageNo]}'>博客列表</a></b><br/><br/>
<div id='post{post.id}' >
<b><a href='{url_base}?g=betterlife&m=blog&a=post&id={post.id}'>
{post.name}</a></b><br/>
<p>{post.content}</p>
评论数:{count_comments(post.id)}
</div>  
{foreach:post.comments,comment}
<div style="padding:10px;margin-top:10px;border:1px solid #cfcfcf;"> 
{comment.comment} <br/>
由 {comment.user.name} 在 {comment.commitTime} 提交<br/>     
</div>                        
{end:}
<font color="{color}">{message}</font><br/>
<h2>提交新评论</h2>                      
<form method="POST">
    我要发言: <br/><input type="hidden" name="id" value="{post.id}"/>
    <textarea rows="5" cols="60" name="comment" flexy:ignoreonly="no"></textarea><br/>
    <input type="submit" />
</form>
<!--Smarty 模板的写法--> *}

{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body} 
    <script>//showHtmlEditor("commentForm","comment");</script>    
    <div id="content">
        <a href='{$url_base}?go=betterlife.auth.logout'><b>退出</b></a><br/>
        <a href='{$url_base}?go=betterlife.blog.display&pageNo={$smarty.get.pageNo|default:"1"}'><b>博客列表</b></a>
        <div id='post{$post.id}' >
            <h1>{$post.name}</h1>
            <p>{$post.content|nl2br}</p>
            评论数:{$viewObject->count_comments($post.id)}
        </div>  
        {foreach item=comment from=$post.comments} 
        <div>
            <blockquote>{$comment.comment|nl2br} <br/>
            由 {$comment.user.name} 在 {$comment.commitTime} 提交<br/>     
            </blockquote>
        </div>        
        {/foreach}
        {if !$post.canEdit}
        <div>
            <font color="{$color|default:'white'}">{$message|default:""}</font><br/>
            <h2>提交新评论</h2>                      
            <form name="commentForm" method="POST">
                我要发言: <br/><input type="hidden" name="id" value="{$post.id}"/>
                <textarea name="comment" id="comment" style="width:700px;height:300px;visibility:hidden;"></textarea><br/>
                <input type="submit" />
            </form>   
        </div> 
        {/if} 
    </div>
{/block}
