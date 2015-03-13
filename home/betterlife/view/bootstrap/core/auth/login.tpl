{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}

<form method="POST">
<div class="col-lg-6" style="margin-left:300px;margin-top:180px;">
	<h2></h2>
	<div class="bs-component">
		<div class="modal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Betterlife CMS 框架前台</h4>
					</div>
					<div class="modal-body" style="height:100px;">
					   <label>用户名</label><input class="inputNormal" type="text" name="username" style="width:260px;" /><br/><br/>
					   <label>密&nbsp;&nbsp;&nbsp;&nbsp;码</label><input class="inputNormal" type="password" name="password" style="width:260px;" /><br/>
					   <br/><br/><font style="margin-left:80px;" color="red">{$message}</font>
					</div>
					<div class="modal-footer">
						<input type="submit" name="Submit" value="登录" class="btnSubmit" />
						<button type="button" class="btn btn-primary" style="width:100px;" onclick="javascript:window.location.href='{$url_base}index.php?go={$appName}.auth.register'">注册</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div align="center">[测试帐户]用户名:admin,密码:admin<br/>[测试帐户]用户名:china,密码:iloveu</div>
</div>
</form>
{/block}