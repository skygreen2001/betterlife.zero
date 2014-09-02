	<flexy:include src="../../layout/normal/header.tpl"></flexy:include>  
	<script>showHtmlEditor("commentForm","comment");</script>              
	<b><my:a href='{url_base}?go=betterlife.auth.logout'>退出</my:a></b><br/>
	<b><my:a href='{url_base}?go=betterlife.blog.display&pageNo={_GET[pageNo]}'>博客列表</my:a></b><br/><br/>
	<div id='blog{blog.blog_id}' >
	<b>{blog.blog_name}</b><br/>
	<p>{blog.content}</p>
	评论数:{count_comments(blog.id)}
	</div>  
	{foreach:blog.comments,comment}
	<div style="padding:10px;margin-top:10px;border:1px solid #cfcfcf;"> 
	{comment.comment} <br/>
	由 {comment.user.username} 在 {comment.commitTime} 提交<br/>     
	</div>                        
	{end:}
	<font color="{color}">{message}</font><br/>
	<h2>提交新评论</h2>                      
	<form method="POST">
		我要发言: <br/><input type="hidden" name="blog_id" value="{blog.blog_id}"/>
		<textarea rows="5" cols="60" name="comment" flexy:ignoreonly="no"></textarea><br/>
		<input type="submit" />
	</form>             
	<flexy:include src="../../layout/normal/footer.tpl"></flexy:include>  