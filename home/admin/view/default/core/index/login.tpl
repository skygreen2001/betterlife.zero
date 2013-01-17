{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
	<div id="main">
		<form method="post">
		<div id="content">
			<table class="content">
				<tr class="left">
					<td class="leftContent" align="center"><img src="{$template_url}resources/images/logo.png" class="logoLeft" alt="{$site_name}后台管理" /></td>
					<td class="right">
						<table class="right">
							<tr align="center"><td class="title" align="center"><b>{$site_name}后台管理</b></td></tr>
							<tr align="center"><td><font color="#ff0000">{$message|default:''}</font></td></tr>
							<tr align="center"><td><label>用户名&nbsp;&nbsp;&nbsp;</label><input class="inputNormal" type="text" name="username" /><br/></td></tr>
							<tr align="center"><td><label>密&nbsp;&nbsp;码&nbsp;&nbsp;&nbsp;</label><input class="inputNormal" type="password" name="password" /><br/></td></tr>
							<tr align="center"><td><label>图形验证码</label><input class="inputVerify" name="validate" id="validate" size="15" type="text" /><img src="{$url_base}home/admin/src/httpdata/validate.php" name="validateCode" id="validateCode" onclick="changeCode();" style="cursor: pointer;vertical-align:top;"/></td></tr>
							<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a style="cursor: pointer;" onclick="changeCode();">看不清楚？换张图片</a></td></tr>
							<tr><td align="center"><input type="submit" name="Submit" value="登录" class="btnSubmit" /></td></tr>
						</table>
					</td>
				</tr>
			</table>
			<div align="center">[测试账号]管理员名:admin,密码:admin。</div>
		</div>
		</form>
	</div>	

	<script>
		function changeCode(){
			document.getElementById('validateCode').src="{$url_base}home/admin/src/httpdata/validate.php?"+Math.random();
		}
	</script>
{/block}
