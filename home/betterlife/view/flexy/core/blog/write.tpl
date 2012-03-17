	<flexy:include src="../../layout/normal/header.tpl"></flexy:include> 
	<script>showHtmlEditor("postForm","content");</script>
	<b><my:a href="{url_base}index.php?go=betterlife.auth.logout">退出</my:a></b><br/>
	<my:a href="{url_base}index.php?go=betterlife.blog.display&pageNo={_GET[pageNo]}">博客列表</my:a>
	<br/><font color="{color}">{message}</font><br/>
	<form method="POST">
		博文名:<br/>
		<input type="text" name="name" value="{blog.blog_name}"/><br/>
		内容: <br/>
		<textarea rows="5" cols="60" name="content">{blog.content}</textarea><br/>
		<input type="submit" />
	</form>             
	<flexy:include src="../../layout/normal/footer.tpl"></flexy:include>  