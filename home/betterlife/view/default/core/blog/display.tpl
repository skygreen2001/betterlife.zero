{* Flexy 语法写法
<b><a href="{url_base}index.php?g=betterlife&m=auth&a=logout">退出</a></b><br/><br/>
<b>共计{countPosts} 篇博客</b>
{if:posts}
{foreach:posts,post}         
<div id='post{post.id}' style="padding:10px;margin-top:10px;border:1px solid #cfcfcf;">  
<b><a href='{url_base}index.php?g=betterlife&m=blog&a=post&id={post.id}&pageNo={_GET[pageNo]}'>{post.name}</a>[<a href="{url_base}index.php?g=betterlife&m=blog&a=write&id={post.id}&pageNo={_GET[pageNo]}">改</a>][<a href="{url_base}index.php?g=betterlife&m=blog&a=delete&id={post.id}&pageNo={_GET[pageNo]}">删</a>]</b><br/>
{post.content}<br/><br/>  
由 {post.user.name} 在 {post.commitTimeShow} 发表<br/>
评论数:{count_comments(post.id)}<br/>
</div>
{end:}<br/>       
<my:page src='{url_base}index.php?go=betterlife.blog.display' /><br/>
<b><a href='{url_base}index.php?go=betterlife.blog.write&pageNo={_GET[pageNo]}'>新建博客</a></b><br/>
{else:}
无博客，你是第一位!
{end:}  
 <!--Smarty 模板的写法--> 
 *}
{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body} 
    <div>
        <b><a href="{$url_base}index.php?go=betterlife.auth.logout">退出</a></b><br/><br/>
        <b>共计{$countPosts} 篇博客</b>
        {if $posts}
        {foreach item=post from=$posts}         
        <div id='post{$post.id}' class="post"> 
            <b><a href='{$url_base}index.php?go=betterlife.blog.post&id={$post.id}&pageNo={$smarty.get.pageNo|default:"1"}'>{$post.name}</a>[<a href="{$url_base}index.php?go=betterlife.blog.write&id={$post.id}&pageNo={$smarty.get.pageNo|default:"1"}">改</a>][<a href="{$url_base}index.php?go=betterlife.blog.delete&id={$post.id}&pageNo={$smarty.get.pageNo|default:"1"}">删</a>]</b><br/>
            {$post.content|nl2br}<br/><br/>
            由 {$post.user.name} 在 {$post.commitTimeShow} 发表<br/>
            评论数:{$viewObject->count_comments($post.id)}<br/>
        </div>
        {/foreach}<br/>       
        <my:page src='{$url_base}index.php?go=betterlife.blog.display' /><br/>
        <b><a href='{$url_base}index.php?go=betterlife.blog.write&pageNo={$smarty.get.pageNo|default:"1"}'>新建博客</a></b><br/>
        {else}              
        无博客，您是第一位!
        {/if} 
    </div>
{/block}