{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
	<div class="contentBox" align="center">
		<form method="POST">
		<h1>请登录</h1>
		<font color="red">{$message}</font>
		<div>           
		   <label>用户名</label><br/><input class="inputNormal" type="text" name="username" style="width:260px;" /><br/>
		   <label>密码</label><br/><input class="inputNormal" type="password" name="password" /><br/>
		</div>
		<input type="submit" name="Submit" value="登录" class="btnSubmit" />
		</form>
		<my:a href="{$url_base}index.php?go=betterlife.auth.register">注册</my:a>
	</div>
	<div align="center">[测试帐户]用户名:admin,密码:admin<br/>[测试帐户]用户名:china,密码:iloveu</center>
{/block}