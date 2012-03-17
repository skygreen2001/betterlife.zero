	<flexy:include src="../../layout/normal/header.tpl"></flexy:include>  
	<div align="center">                                                           
		<form method="POST">
		<h1>请注册您的账户</h1>
		<font color="{color}">{message}</font>
		<div>              
			<label>用户名</label><br/><input type="text" name="username" /><br/>
			<label>密码</label><br/><input type="password" name="password" /><br/> 
		</div>       
		<input type="submit" name="Submit" value="注册" class="btnSubmit"/>
		</form>
		<my:a href="{url_base}index.php?go=betterlife.auth.login">登录</my:a> 
	</div>                   
	<flexy:include src="../../layout/normal/footer.tpl"></flexy:include>   