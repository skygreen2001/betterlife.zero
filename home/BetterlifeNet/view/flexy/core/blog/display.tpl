	<flexy:include src="../../layout/normal/header.tpl"></flexy:include>   
	<b><my:a href="{url_base}index.php?go=betterlife.auth.logout">退出</my:a></b><br/><br/>
	<b>共计{countBlogs} 篇博客</b>
	{if:blogs}
	{foreach:blogs,blog}         
	<div id='blog{blog.blog_id}' style="padding:10px;margin-top:10px;border:1px solid #cfcfcf;">  
	<b><my:a href='{url_base}index.php?go=betterlife.comment.comment&blog_id={blog.blog_id}&pageNo={_GET[pageNo]}'>{blog.blog_name}</my:a>[<my:a href="{url_base}index.php?go=betterlife.blog.write&blog_id={blog.blog_id}&pageNo={_GET[pageNo]}">改</my:a>][<my:a href="{url_base}index.php?go=betterlife.blog.delete&blog_id={blog.blog_id}&pageNo={_GET[pageNo]}">删</my:a>]</b><br/>
	{blog.content}<br/><br/>  
	由 {blog.user.username} 在 {blog.commitTime} 发表<br/>
	评论数:{count_comments(blog.blog_id)}<br/>
	</div>
	{end:}<br/>       
	<my:page src='{url_base}index.php?go=betterlife.blog.display' /><br/>
	<b><my:a href='{url_base}index.php?go=betterlife.blog.write&pageNo={_GET[pageNo]}'>新建博客</my:a></b><br/>
	{else:}
	无博客，你是第一位!
	{end:}         
	<flexy:include src="../../layout/normal/footer.tpl"></flexy:include>   