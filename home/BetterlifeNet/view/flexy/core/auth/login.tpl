	<flexy:include src="../../layout/normal/header.tpl"></flexy:include>   
	<div align="center">                      
		<form method="POST">         
		<h1>请登录</h1>
		<font color="red">{message}</font>
		<div>                
			用户名:<br/><input type="text" name="username"/><br/>
			密码: <br/><input type="password" name="password" /><br/>
		</div>     
		<input type="submit" name="Submit" value="登录" class="btnSubmit" />
		</form>
		<my:a href="{url_base}index.php?go=betterlife.auth.register">注册</my:a>      
	</div>                          
	<flexy:include src="../../layout/normal/footer.tpl"></flexy:include>   